<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\GuruDashboardFilterRequest;
use App\Models\Guru;
use App\Models\PelanggaranSiswa;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class GuruDashboardController extends Controller
{
    public function __invoke(GuruDashboardFilterRequest $request): View
    {
        $teacher = Guru::query()
            ->where('user_id', $request->user()->id)
            ->where('status_aktif', true)
            ->firstOrFail();

        $academicYears = TahunAjaran::query()
            ->select(['id', 'nama_tahun_ajaran', 'tanggal_mulai', 'tanggal_selesai', 'status_aktif'])
            ->orderByDesc('status_aktif')
            ->orderByDesc('tanggal_mulai')
            ->get();

        $requestedAcademicYearId = $request->validated('tahun_ajaran_id');
        $selectedAcademicYear = $requestedAcademicYearId !== null
            ? $academicYears->firstWhere('id', (int) $requestedAcademicYearId)
            : ($academicYears->firstWhere('status_aktif', true) ?? $academicYears->first());

        $stats = ['total_records' => 0, 'active_records' => 0, 'cancelled_records' => 0];
        $recentRecords = collect();
        $homeroomAssignment = null;

        if ($selectedAcademicYear !== null) {
            $statsResult = PelanggaranSiswa::query()
                ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
                ->where('t_kelas_siswa.tahun_ajaran_id', $selectedAcademicYear->id)
                ->where('t_pelanggaran_siswa.guru_id', $teacher->id)
                ->selectRaw('COUNT(t_pelanggaran_siswa.id) AS total_records')
                ->selectRaw("SUM(CASE WHEN t_pelanggaran_siswa.status = 'aktif' THEN 1 ELSE 0 END) AS active_records")
                ->selectRaw("SUM(CASE WHEN t_pelanggaran_siswa.status = 'dibatalkan' THEN 1 ELSE 0 END) AS cancelled_records")
                ->first();

            $stats = [
                'total_records' => (int) $statsResult->total_records,
                'active_records' => (int) $statsResult->active_records,
                'cancelled_records' => (int) $statsResult->cancelled_records,
            ];

            $recentRecords = PelanggaranSiswa::query()
                ->with([
                    'penempatanKelas:id,siswa_id,kelas_id,tahun_ajaran_id',
                    'penempatanKelas.siswa:id,nisn,nama',
                    'penempatanKelas.kelas:id,nama_kelas',
                    'pelanggaran:id,nama_pelanggaran',
                ])
                ->where('guru_id', $teacher->id)
                ->whereHas('penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery
                    ->where('tahun_ajaran_id', $selectedAcademicYear->id))
                ->orderByDesc('tanggal_dicatat')
                ->orderByDesc('id')
                ->limit(6)
                ->get();

            $homeroomAssignment = WaliKelas::query()
                ->with(['kelas:id,nama_kelas', 'tahunAjaran:id,nama_tahun_ajaran'])
                ->where('guru_id', $teacher->id)
                ->where('tahun_ajaran_id', $selectedAcademicYear->id)
                ->where('status_aktif', true)
                ->whereDate('tanggal_mulai', '<=', $selectedAcademicYear->tanggal_selesai)
                ->where(static function (Builder $query) use ($selectedAcademicYear): void {
                    $query->whereNull('tanggal_selesai')
                        ->orWhereDate('tanggal_selesai', '>=', $selectedAcademicYear->tanggal_mulai);
                })
                ->orderByDesc('tanggal_mulai')
                ->orderByDesc('id')
                ->first();
        }

        return view('guru.dashboard', [
            'teacher' => $teacher,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'stats' => $stats,
            'recentRecords' => $recentRecords,
            'homeroomAssignment' => $homeroomAssignment,
            'hasActiveWaliKelas' => $homeroomAssignment !== null,
            'quickLinks' => [
                ['label' => 'Cari Siswa', 'route' => 'guru.students.index'],
                ['label' => 'Riwayat Pelanggaran', 'route' => 'guru.violations.index'],
            ],
        ]);
    }
}
