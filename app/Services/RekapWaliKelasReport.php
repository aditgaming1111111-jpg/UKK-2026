<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\KelasSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RekapWaliKelasReport
{
    /**
     * @param  array<string, int|string|null>  $filters
     * @return array<string, mixed>
     */
    public function index(User $user, array $filters): array
    {
        $context = $this->context($user, $filters);
        $placements = $this->placements($context);
        $activeNotes = $this->notes($context, $placements)->where('t_pelanggaran_siswa.status', 'aktif');
        $aggregate = (clone $activeNotes)
            ->select([])
            ->selectRaw('COUNT(t_pelanggaran_siswa.id) AS active_records')
            ->selectRaw('COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
            ->selectRaw('COUNT(DISTINCT t_kelas_siswa.siswa_id) AS students_with_active_records')
            ->first();

        $studentCount = DB::query()
            ->fromSub((clone $placements)->select('siswa_id')->distinct(), 'recap_students')
            ->count();

        $perStudent = (clone $activeNotes)
            ->select([])
            ->selectRaw('t_kelas_siswa.siswa_id, COUNT(t_pelanggaran_siswa.id) AS active_records, COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
            ->groupBy('t_kelas_siswa.siswa_id');

        $students = Siswa::query()
            ->leftJoinSub($perStudent->toBase(), 'recap_active', 'recap_active.siswa_id', '=', 't_siswa.id')
            ->whereIn('t_siswa.id', (clone $placements)->select('siswa_id')->distinct())
            ->when(($filters['q'] ?? '') !== '', static function (Builder $query) use ($filters): void {
                $term = addcslashes(trim((string) $filters['q']), '%_\\');
                $query->where(static function (Builder $searchQuery) use ($term): void {
                    $searchQuery->where('t_siswa.nisn', 'like', '%'.$term.'%')
                        ->orWhere('t_siswa.nama', 'like', '%'.$term.'%');
                });
            })
            ->select(['t_siswa.id', 't_siswa.nisn', 't_siswa.nama'])
            ->selectRaw('COALESCE(recap_active.active_records, 0) AS active_records')
            ->selectRaw('COALESCE(recap_active.active_points, 0) AS active_points')
            ->orderBy('t_siswa.nama')
            ->orderBy('t_siswa.id')
            ->paginate(10)
            ->withQueryString();

        return [
            ...$context,
            'students' => $students,
            'summary' => [
                'student_count' => (int) $studentCount,
                'students_with_active_records' => (int) ($aggregate->students_with_active_records ?? 0),
                'active_records' => (int) ($aggregate->active_records ?? 0),
                'active_points' => (int) ($aggregate->active_points ?? 0),
            ],
        ];
    }

    /** @param array<string, int|string|null> $filters
     * @return array<string, mixed>
     */
    public function exportData(User $user, int $yearId, int $classId, array $filters): array
    {
        $context = $this->context($user, [
            ...$filters,
            'tahun_ajaran_id' => $yearId,
            'kelas_id' => $classId,
        ]);
        $placements = $this->placements($context);
        $activeNotes = $this->notes($context, $placements)->where('t_pelanggaran_siswa.status', 'aktif');
        $aggregate = (clone $activeNotes)
            ->select([])
            ->selectRaw('COUNT(t_pelanggaran_siswa.id) AS active_records')
            ->selectRaw('COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
            ->selectRaw('COUNT(DISTINCT t_kelas_siswa.siswa_id) AS students_with_active_records')
            ->first();
        $studentCount = DB::query()
            ->fromSub((clone $placements)->select('siswa_id')->distinct(), 'recap_students')
            ->count();
        $perStudent = (clone $activeNotes)
            ->select([])
            ->selectRaw('t_kelas_siswa.siswa_id, COUNT(t_pelanggaran_siswa.id) AS active_records, COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
            ->groupBy('t_kelas_siswa.siswa_id');
        $students = Siswa::query()
            ->leftJoinSub($perStudent->toBase(), 'recap_active', 'recap_active.siswa_id', '=', 't_siswa.id')
            ->whereIn('t_siswa.id', (clone $placements)->select('siswa_id')->distinct())
            ->select(['t_siswa.id', 't_siswa.nisn', 't_siswa.nama'])
            ->selectRaw('COALESCE(recap_active.active_records, 0) AS active_records')
            ->selectRaw('COALESCE(recap_active.active_points, 0) AS active_points')
            ->orderBy('t_siswa.nama')
            ->orderBy('t_siswa.id')
            ->get();
        $records = $this->notes($context, $placements)
            ->with([
                'penempatanKelas.siswa:id,nisn,nama',
                'penempatanKelas.kelas:id,nama_kelas',
                'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
                'pelanggaran:id,kategori_id,kode,nama_pelanggaran',
                'pelanggaran.kategori:id,nama_kategori',
                'guru:id,nama',
                'logPerubahan.pengguna:id,nama',
            ])
            ->orderBy('t_pelanggaran_siswa.tanggal_pelanggaran')
            ->orderBy('t_pelanggaran_siswa.id');

        return [
            ...$context,
            'students' => $students,
            'records' => $records,
            'summary' => [
                'student_count' => (int) $studentCount,
                'students_with_active_records' => (int) ($aggregate->students_with_active_records ?? 0),
                'active_records' => (int) ($aggregate->active_records ?? 0),
                'active_points' => (int) ($aggregate->active_points ?? 0),
            ],
        ];
    }

    /**
     * @param  array<string, int|string|null>  $filters
     * @return array<string, mixed>
     */
    public function student(User $user, int $yearId, int $classId, Siswa $student, array $filters): array
    {
        $context = $this->context($user, [
            ...$filters,
            'tahun_ajaran_id' => $yearId,
            'kelas_id' => $classId,
        ]);
        $placements = $this->placements($context);

        if (! (clone $placements)->where('siswa_id', $student->id)->exists()) {
            throw new NotFoundHttpException('Siswa tidak ditemukan dalam cakupan rekap ini.');
        }

        $recordsQuery = $this->notes($context, $placements)
            ->where('t_kelas_siswa.siswa_id', $student->id)
            ->with([
                'penempatanKelas.kelas:id,nama_kelas,tingkat,jurusan',
                'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
                'pelanggaran:id,kategori_id,kode,nama_pelanggaran',
                'pelanggaran.kategori:id,nama_kategori',
                'guru:id,nama',
                'logPerubahan.pengguna:id,nama',
            ])
            ->orderByDesc('t_pelanggaran_siswa.tanggal_pelanggaran')
            ->orderByDesc('t_pelanggaran_siswa.id');

        $totals = (clone $recordsQuery)
            ->where('t_pelanggaran_siswa.status', 'aktif')
            ->select([])
            ->selectRaw('COUNT(t_pelanggaran_siswa.id) AS active_records')
            ->selectRaw('COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
            ->first();

        return [
            ...$context,
            'student' => $student,
            'summary' => [
                'active_records' => (int) ($totals->active_records ?? 0),
                'active_points' => (int) ($totals->active_points ?? 0),
            ],
            'records' => $recordsQuery->paginate(10)->withQueryString(),
        ];
    }

    /**
     * @param  array<string, int|string|null>  $filters
     * @return array<string, mixed>
     */
    private function context(User $user, array $filters): array
    {
        if ($user->role !== 'guru') {
            throw new AccessDeniedHttpException('Rekap wali kelas hanya tersedia untuk Guru yang ditugaskan.');
        }

        $teacher = Guru::query()
            ->where('user_id', $user->id)
            ->where('status_aktif', true)
            ->first();

        if ($teacher === null) {
            throw new AccessDeniedHttpException('Akun Guru aktif tidak ditemukan.');
        }

        $assignments = WaliKelas::query()
            ->with([
                'kelas:id,nama_kelas,tingkat,jurusan',
                'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai,status_aktif',
            ])
            ->where('guru_id', $teacher->id)
            ->where(static fn (Builder $query): Builder => $query
                ->where('status_aktif', true)
                ->orWhereNotNull('tanggal_selesai'))
            ->get()
            ->filter(static function (WaliKelas $assignment): bool {
                if ($assignment->tahunAjaran === null) {
                    return false;
                }

                return $assignment->tanggal_mulai->lte($assignment->tahunAjaran->tanggal_selesai)
                    && ($assignment->tanggal_selesai === null
                        || $assignment->tanggal_selesai->gte($assignment->tahunAjaran->tanggal_mulai));
            })
            ->sortByDesc(static fn (WaliKelas $assignment): string => $assignment->tahunAjaran->tanggal_mulai->toDateString())
            ->values();

        if ($assignments->isEmpty()) {
            throw new AccessDeniedHttpException('Guru ini tidak memiliki penugasan wali kelas yang valid.');
        }

        $academicYears = $assignments->pluck('tahunAjaran')->unique('id')->values();
        $requestedYearId = isset($filters['tahun_ajaran_id']) ? (int) $filters['tahun_ajaran_id'] : null;
        $selectedYear = $requestedYearId !== null
            ? $academicYears->firstWhere('id', $requestedYearId)
            : ($academicYears->firstWhere('status_aktif', true) ?? $academicYears->first());

        if ($selectedYear === null) {
            throw new AccessDeniedHttpException('Tahun ajaran tersebut tidak termasuk penugasan Guru ini.');
        }

        $yearAssignments = $assignments->where('tahun_ajaran_id', $selectedYear->id)->values();
        $classIds = $yearAssignments->pluck('kelas_id')->unique()->values();
        $requestedClassId = isset($filters['kelas_id']) ? (int) $filters['kelas_id'] : null;
        $selectedClassId = $requestedClassId ?? (int) $classIds->first();

        if (! $classIds->contains($selectedClassId)) {
            throw new AccessDeniedHttpException('Kelas tersebut tidak termasuk penugasan Guru pada tahun ajaran ini.');
        }

        $classAssignments = $yearAssignments->where('kelas_id', $selectedClassId)->values();

        return [
            'teacher' => $teacher,
            'academicYears' => $academicYears,
            'selectedYear' => $selectedYear,
            'classes' => $yearAssignments->pluck('kelas')->unique('id')->values(),
            'selectedClass' => $classAssignments->first()->kelas,
            'assignments' => $classAssignments,
            'assignmentIds' => $classAssignments->pluck('id')->map(static fn ($id): int => (int) $id)->all(),
            'filters' => $filters,
        ];
    }

    /** @param array<string, mixed> $context
     * @return Builder<KelasSiswa>
     */
    private function placements(array $context): Builder
    {
        /** @var TahunAjaran $year */
        $year = $context['selectedYear'];

        $query = KelasSiswa::query()
            ->where('tahun_ajaran_id', $year->id)
            ->where('kelas_id', $context['selectedClass']->id)
            ->whereDate('tanggal_mulai', '<=', $year->tanggal_selesai)
            ->where(static function (Builder $query) use ($year): void {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $year->tanggal_mulai);
            })
            ->whereExists(static function (QueryBuilder $assignmentQuery) use ($context): void {
                $assignmentQuery->selectRaw('1')
                    ->from('t_wali_kelas as recap_assignment')
                    ->whereIn('recap_assignment.id', $context['assignmentIds'])
                    ->whereColumn('recap_assignment.kelas_id', 't_kelas_siswa.kelas_id')
                    ->whereColumn('recap_assignment.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                    ->where(static function (QueryBuilder $query): void {
                        $query->whereNull('t_kelas_siswa.tanggal_selesai')
                            ->orWhereColumn('recap_assignment.tanggal_mulai', '<=', 't_kelas_siswa.tanggal_selesai');
                    })
                    ->where(static function (QueryBuilder $query): void {
                        $query->whereNull('recap_assignment.tanggal_selesai')
                            ->orWhereColumn('t_kelas_siswa.tanggal_mulai', '<=', 'recap_assignment.tanggal_selesai');
                    });
            })
            ->select(['id', 'siswa_id', 'kelas_id', 'tahun_ajaran_id', 'tanggal_mulai', 'tanggal_selesai']);

        if (($context['filters']['q'] ?? '') !== '') {
            $term = addcslashes(trim((string) $context['filters']['q']), '%_\\');
            $query->whereHas('siswa', static fn (Builder $studentQuery): Builder => $studentQuery
                ->where('nisn', 'like', '%'.$term.'%')
                ->orWhere('nama', 'like', '%'.$term.'%'));
        }

        return $query;
    }

    /** @param array<string, mixed> $context
     * @param  Builder<KelasSiswa>  $placements
     * @return Builder<PelanggaranSiswa>
     */
    private function notes(array $context, Builder $placements): Builder
    {
        /** @var TahunAjaran $year */
        $year = $context['selectedYear'];
        $filters = $context['filters'];

        return PelanggaranSiswa::query()
            ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
            ->whereIn('t_pelanggaran_siswa.kelas_siswa_id', (clone $placements)->select('id'))
            ->whereBetween('t_pelanggaran_siswa.tanggal_pelanggaran', [
                $year->tanggal_mulai->toDateString(),
                $year->tanggal_selesai->toDateString(),
            ])
            ->whereColumn('t_kelas_siswa.tanggal_mulai', '<=', 't_pelanggaran_siswa.tanggal_pelanggaran')
            ->where(static function (Builder $query): void {
                $query->whereNull('t_kelas_siswa.tanggal_selesai')
                    ->orWhereColumn('t_kelas_siswa.tanggal_selesai', '>=', 't_pelanggaran_siswa.tanggal_pelanggaran');
            })
            ->whereExists(static function (QueryBuilder $assignmentQuery) use ($context): void {
                $assignmentQuery->selectRaw('1')
                    ->from('t_wali_kelas as recap_assignment')
                    ->whereIn('recap_assignment.id', $context['assignmentIds'])
                    ->whereColumn('recap_assignment.kelas_id', 't_kelas_siswa.kelas_id')
                    ->whereColumn('recap_assignment.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                    ->whereColumn('recap_assignment.tanggal_mulai', '<=', 't_pelanggaran_siswa.tanggal_pelanggaran')
                    ->where(static function (QueryBuilder $query): void {
                        $query->whereNull('recap_assignment.tanggal_selesai')
                            ->orWhereColumn('recap_assignment.tanggal_selesai', '>=', 't_pelanggaran_siswa.tanggal_pelanggaran');
                    });
            })
            ->when($filters['tanggal_mulai'] ?? null, static fn (Builder $query, string $date): Builder => $query
                ->whereDate('t_pelanggaran_siswa.tanggal_pelanggaran', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, static fn (Builder $query, string $date): Builder => $query
                ->whereDate('t_pelanggaran_siswa.tanggal_pelanggaran', '<=', $date))
            ->select('t_pelanggaran_siswa.*');
    }
}
