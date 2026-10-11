<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\RekapKelasRequest;
use App\Models\Guru;
use App\Models\KelasSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\WaliKelas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\View\View;

class RekapKelasController extends Controller
{
    public function index(RekapKelasRequest $request): View
    {
        $teacher = Guru::query()
            ->where('user_id', $request->user()->id)
            ->where('status_aktif', true)
            ->firstOrFail();

        $assignments = WaliKelas::query()
            ->with(['kelas:id,nama_kelas,tingkat,jurusan', 'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai,status_aktif'])
            ->where('guru_id', $teacher->id)
            ->where(static fn (Builder $query): Builder => $query->where('status_aktif', true)->orWhereNotNull('tanggal_selesai'))
            ->get()
            ->filter(static function (WaliKelas $assignment): bool {
                if ($assignment->tahunAjaran === null) {
                    return false;
                }

                return $assignment->tanggal_mulai->lte($assignment->tahunAjaran->tanggal_selesai)
                    && ($assignment->tanggal_selesai === null
                        || $assignment->tanggal_selesai->gte($assignment->tahunAjaran->tanggal_mulai));
            })
            ->sortByDesc(fn (WaliKelas $assignment): string => $assignment->tahunAjaran->tanggal_mulai->toDateString())
            ->values();

        abort_if($assignments->isEmpty(), 403, 'Akun ini tidak memiliki penugasan wali kelas yang valid.');

        $years = $assignments->pluck('tahunAjaran')->unique('id')->values();
        $requestedYearId = $request->validated('tahun_ajaran_id');
        $selectedYear = $requestedYearId !== null
            ? $years->firstWhere('id', (int) $requestedYearId)
            : ($years->firstWhere('status_aktif', true) ?? $years->first());

        abort_if($selectedYear === null, 403, 'Tidak ada penugasan wali kelas untuk tahun ajaran yang diminta.');

        $yearAssignments = $assignments->where('tahun_ajaran_id', $selectedYear->id)->values();
        abort_if($yearAssignments->isEmpty(), 403, 'Anda tidak memiliki penugasan pada tahun ajaran tersebut.');

        $assignmentIds = $yearAssignments->pluck('id')->map(static fn ($id): int => (int) $id)->all();
        $filters = $request->validated();
        $placementQuery = KelasSiswa::query()
            ->with(['siswa:id,nisn,nama,status_siswa', 'kelas:id,nama_kelas,tingkat,jurusan'])
            ->where('tahun_ajaran_id', $selectedYear->id)
            ->whereIn('kelas_id', $yearAssignments->pluck('kelas_id')->unique())
            ->whereExists(static function (QueryBuilder $assignmentQuery) use ($assignmentIds, $selectedYear): void {
                $assignmentQuery->selectRaw('1')
                    ->from('t_wali_kelas as recap_assignment')
                    ->whereIn('recap_assignment.id', $assignmentIds)
                    ->whereColumn('recap_assignment.kelas_id', 't_kelas_siswa.kelas_id')
                    ->whereColumn('recap_assignment.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                    ->where(static function (QueryBuilder $startQuery) use ($selectedYear): void {
                        $startQuery->where(static fn (QueryBuilder $query): QueryBuilder => $query
                            ->whereNull('recap_assignment.tanggal_selesai')
                            ->whereDate('t_kelas_siswa.tanggal_mulai', '<=', $selectedYear->tanggal_selesai))
                            ->orWhereColumn('t_kelas_siswa.tanggal_mulai', '<=', 'recap_assignment.tanggal_selesai');
                    })
                    ->where(static function (QueryBuilder $endQuery): void {
                        $endQuery->whereNull('t_kelas_siswa.tanggal_selesai')
                            ->orWhereColumn('t_kelas_siswa.tanggal_selesai', '>=', 'recap_assignment.tanggal_mulai');
                    });
            });

        if (($filters['q'] ?? '') !== '') {
            $term = addcslashes(trim($filters['q']), '%_\\');
            $placementQuery->whereHas('siswa', static fn (Builder $studentQuery): Builder => $studentQuery
                ->where('nisn', 'like', '%'.$term.'%')
                ->orWhere('nama', 'like', '%'.$term.'%'));
        }

        $activeNotes = PelanggaranSiswa::query()
            ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
            ->where('t_kelas_siswa.tahun_ajaran_id', $selectedYear->id)
            ->where('t_pelanggaran_siswa.status', 'aktif')
            ->whereIn('t_pelanggaran_siswa.kelas_siswa_id', (clone $placementQuery)->select('t_kelas_siswa.id'))
            ->whereExists(static function (QueryBuilder $assignmentQuery) use ($assignmentIds): void {
                $assignmentQuery->selectRaw('1')
                    ->from('t_wali_kelas as recap_assignment')
                    ->whereIn('recap_assignment.id', $assignmentIds)
                    ->whereColumn('recap_assignment.kelas_id', 't_kelas_siswa.kelas_id')
                    ->whereColumn('recap_assignment.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                    ->whereColumn('recap_assignment.tanggal_mulai', '<=', 't_pelanggaran_siswa.tanggal_pelanggaran')
                    ->where(static function (QueryBuilder $periodQuery): void {
                        $periodQuery->whereNull('recap_assignment.tanggal_selesai')
                            ->orWhereColumn('recap_assignment.tanggal_selesai', '>=', 't_pelanggaran_siswa.tanggal_pelanggaran');
                    });
            });

        $perPlacement = (clone $activeNotes)
            ->selectRaw('t_pelanggaran_siswa.kelas_siswa_id, COUNT(t_pelanggaran_siswa.id) AS active_records, SUM(t_pelanggaran_siswa.poin_saat_dicatat) AS active_points')
            ->groupBy('t_pelanggaran_siswa.kelas_siswa_id')
            ->get()
            ->keyBy('kelas_siswa_id');
        $summary = [
            'active_records' => (int) $perPlacement->sum('active_records'),
            'active_points' => (int) $perPlacement->sum('active_points'),
        ];

        $students = (clone $placementQuery)
            ->orderBy('t_kelas_siswa.kelas_id')
            ->orderBy('t_kelas_siswa.siswa_id')
            ->orderByDesc('t_kelas_siswa.tanggal_mulai')
            ->paginate(20)
            ->withQueryString();
        $students->getCollection()->each(static function (KelasSiswa $placement) use ($perPlacement): void {
            $placement->setAttribute('active_records', (int) ($perPlacement->get($placement->id)->active_records ?? 0));
            $placement->setAttribute('active_points', (int) ($perPlacement->get($placement->id)->active_points ?? 0));
        });

        return view('guru.rekap-kelas.index', [
            'teacher' => $teacher,
            'selectedYear' => $selectedYear,
            'academicYears' => $years,
            'assignments' => $yearAssignments,
            'students' => $students,
            'summary' => $summary,
            'filters' => $filters,
        ]);
    }
}
