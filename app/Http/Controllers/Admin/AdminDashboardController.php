<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DashboardFilterRequest;
use App\Models\Guru;
use App\Models\LogPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(DashboardFilterRequest $request): View
    {
        $academicYears = TahunAjaran::query()
            ->select(['id', 'nama_tahun_ajaran', 'tanggal_mulai', 'status_aktif'])
            ->orderByDesc('status_aktif')
            ->orderByDesc('tanggal_mulai')
            ->get();

        $requestedAcademicYearId = $request->validated('tahun_ajaran_id');
        $selectedAcademicYear = $requestedAcademicYearId !== null
            ? $academicYears->firstWhere('id', (int) $requestedAcademicYearId)
            : ($academicYears->firstWhere('status_aktif', true) ?? $academicYears->first());

        $activeStudents = Siswa::query()
            ->where('status_siswa', 'aktif')
            ->count();

        $activeTeachers = Guru::query()
            ->where('status_aktif', true)
            ->whereHas('user', static fn (Builder $userQuery): Builder => $userQuery->where('role', 'guru'))
            ->count();

        $violationTotals = ['count' => 0, 'points' => 0];
        $categorySummary = collect();
        $recentViolations = collect();
        $recentActivities = collect();

        if ($selectedAcademicYear !== null) {
            $violationTotalsResult = PelanggaranSiswa::query()
                ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
                ->where('t_kelas_siswa.tahun_ajaran_id', $selectedAcademicYear->id)
                ->where('t_pelanggaran_siswa.status', 'aktif')
                ->selectRaw('COUNT(t_pelanggaran_siswa.id) AS active_violations')
                ->selectRaw('COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
                ->first();

            $violationTotals = [
                'count' => (int) $violationTotalsResult->active_violations,
                'points' => (int) $violationTotalsResult->active_points,
            ];

            $categorySummary = PelanggaranSiswa::query()
                ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
                ->join('t_pelanggaran', 't_pelanggaran.id', '=', 't_pelanggaran_siswa.pelanggaran_id')
                ->join('t_pelanggaran_kategori', 't_pelanggaran_kategori.id', '=', 't_pelanggaran.kategori_id')
                ->where('t_kelas_siswa.tahun_ajaran_id', $selectedAcademicYear->id)
                ->where('t_pelanggaran_siswa.status', 'aktif')
                ->select([
                    't_pelanggaran_kategori.id',
                    't_pelanggaran_kategori.nama_kategori',
                ])
                ->selectRaw('COUNT(t_pelanggaran_siswa.id) AS total_pelanggaran')
                ->selectRaw('SUM(t_pelanggaran_siswa.poin_saat_dicatat) AS total_poin')
                ->groupBy('t_pelanggaran_kategori.id', 't_pelanggaran_kategori.nama_kategori')
                ->orderByDesc('total_pelanggaran')
                ->orderBy('t_pelanggaran_kategori.nama_kategori')
                ->get();

            $recentViolations = PelanggaranSiswa::query()
                ->with([
                    'penempatanKelas:id,siswa_id,kelas_id,tahun_ajaran_id',
                    'penempatanKelas.siswa:id,nisn,nama',
                    'penempatanKelas.kelas:id,nama_kelas',
                    'pelanggaran:id,nama_pelanggaran',
                    'guru:id,nama',
                ])
                ->whereHas('penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery
                    ->where('tahun_ajaran_id', $selectedAcademicYear->id))
                ->orderByDesc('tanggal_dicatat')
                ->orderByDesc('id')
                ->limit(8)
                ->get();

            $recentActivities = LogPelanggaran::query()
                ->with([
                    'pengguna:id,nama',
                    'catatanPelanggaran:id,kelas_siswa_id,pelanggaran_id',
                    'catatanPelanggaran.penempatanKelas:id,siswa_id,tahun_ajaran_id',
                    'catatanPelanggaran.penempatanKelas.siswa:id,nama',
                    'catatanPelanggaran.pelanggaran:id,nama_pelanggaran',
                ])
                ->whereHas('catatanPelanggaran.penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery
                    ->where('tahun_ajaran_id', $selectedAcademicYear->id))
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(8)
                ->get();
        }

        return view('admin.dashboard', [
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'stats' => [
                'active_students' => $activeStudents,
                'active_teachers' => $activeTeachers,
                'active_violations' => $violationTotals['count'],
                'active_points' => $violationTotals['points'],
            ],
            'categorySummary' => $categorySummary,
            'recentViolations' => $recentViolations,
            'recentActivities' => $recentActivities,
            'quickLinks' => [
                ['label' => 'Data Siswa', 'route' => 'admin.students.index'],
                ['label' => 'Data Pelanggaran', 'route' => 'admin.student-violations.index'],
                ['label' => 'Laporan', 'route' => 'admin.reports.index'],
            ],
        ]);
    }
}
