<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

/**
 * Menyediakan daftar, rincian, dan rekap poin siswa untuk kebutuhan baca.
 */
class SiswaReadService
{
    /**
     * Menyediakan direktori siswa terfilter, lengkap dengan poin aktif dan akses rincian.
     *
     * @param  array{q?: string|null, kelas_id?: int|string|null, tingkat?: string|null, jurusan?: string|null, status?: string|null, tahun_ajaran_id?: int|string|null}  $filters
     * @return LengthAwarePaginator<int, Siswa>
     */
    public function directory(array $filters, User $user): LengthAwarePaginator
    {
        $classId = $filters['kelas_id'] ?? null;
        $level = $filters['tingkat'] ?? null;
        $major = $filters['jurusan'] ?? null;
        $academicYearId = $filters['tahun_ajaran_id'] ?? null;
        // Poin dihitung dari snapshot catatan aktif pada tahun ajaran yang dipilih.
        $activePoints = PelanggaranSiswa::query()
            ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
            ->where('t_pelanggaran_siswa.status', 'aktif')
            ->when($academicYearId !== null, static fn (Builder $query): Builder => $query->where('t_kelas_siswa.tahun_ajaran_id', $academicYearId), static fn (Builder $query): Builder => $query->whereRaw('1 = 0'))
            ->selectRaw('t_kelas_siswa.siswa_id, COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
            ->groupBy('t_kelas_siswa.siswa_id');

        $students = Siswa::query()
            ->select(['id', 'nis', 'nisn', 'nama', 'jenis_kelamin', 'status_siswa'])
            ->leftJoinSub($activePoints->toBase(), 'student_active_points', 'student_active_points.siswa_id', '=', 't_siswa.id')
            ->addSelect('student_active_points.active_points')
            ->with(['penempatanKelas' => static fn (Relation $query): Relation => $query
                ->select(['id', 'siswa_id', 'kelas_id', 'status_aktif', 'tanggal_selesai'])
                ->where('status_aktif', true)
                ->whereNull('tanggal_selesai')
                ->with('kelas:id,nama_kelas,tingkat,jurusan')])
            ->when($filters['q'] ?? null, static fn (Builder $query, string $search): Builder => $query
                ->where(static function (Builder $searchQuery) use ($search): void {
                    $searchQuery->where('nis', 'like', '%'.$search.'%')
                        ->orWhere('nisn', 'like', '%'.$search.'%')
                        ->orWhere('nama', 'like', '%'.$search.'%');
                }))
            ->when($filters['status'] ?? null, static fn (Builder $query, string $status): Builder => $query
                ->where('status_siswa', $status))
            ->when($classId || $level || $major, static fn (Builder $query): Builder => $query
                ->whereHas('penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery
                    ->where('status_aktif', true)
                    ->whereNull('tanggal_selesai')
                    ->when($classId, static fn (Builder $placementQuery): Builder => $placementQuery->where('kelas_id', $classId))
                    ->when($level || $major, static fn (Builder $placementQuery): Builder => $placementQuery
                        ->whereHas('kelas', static fn (Builder $classQuery): Builder => $classQuery
                            ->when($level, static fn (Builder $classQuery): Builder => $classQuery->where('tingkat', $level))
                            ->when($major, static fn (Builder $classQuery): Builder => $classQuery->where('jurusan', $major))))))
            ->orderBy('nama')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $viewableStudentIds = $this->viewableStudentIds($user, $students->getCollection()->modelKeys());

        foreach ($students as $student) {
            $student->setAttribute('can_view_details', in_array($student->id, $viewableStudentIds, true));
            $student->setAttribute('active_points', (int) ($student->active_points ?? 0));
        }

        return $students;
    }

    /**
     * Menyusun rekap poin aktif siswa untuk tahun ajaran dan pencarian yang dipilih.
     *
     * @param  array{q?: string|null, tahun_ajaran_id?: int|string|null}  $filters
     * @return LengthAwarePaginator<int, Siswa>
     */
    public function pointRecap(array $filters): LengthAwarePaginator
    {
        $academicYearId = $filters['tahun_ajaran_id'] ?? null;
        $activePoints = PelanggaranSiswa::query()
            ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
            ->where('t_pelanggaran_siswa.status', 'aktif')
            ->when(
                $academicYearId !== null,
                static fn (Builder $query): Builder => $query->where('t_kelas_siswa.tahun_ajaran_id', $academicYearId),
                static fn (Builder $query): Builder => $query->whereRaw('1 = 0'),
            )
            ->selectRaw('t_kelas_siswa.siswa_id, COALESCE(SUM(t_pelanggaran_siswa.poin_saat_dicatat), 0) AS active_points')
            ->groupBy('t_kelas_siswa.siswa_id');

        return Siswa::query()
            ->select(['t_siswa.id', 't_siswa.nisn', 't_siswa.nama', 't_siswa.status_siswa'])
            ->leftJoinSub($activePoints->toBase(), 'student_active_points', 'student_active_points.siswa_id', '=', 't_siswa.id')
            ->addSelect('student_active_points.active_points')
            ->when($filters['q'] ?? null, static function (Builder $query, string $search): void {
                $query->where(static function (Builder $searchQuery) use ($search): void {
                    $searchQuery->where('t_siswa.nisn', 'like', '%'.$search.'%')
                        ->orWhere('t_siswa.nama', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('t_siswa.nama')
            ->orderBy('t_siswa.id')
            ->paginate(10)
            ->withQueryString()
            ->through(static function (Siswa $student): Siswa {
                $student->setAttribute('active_points', (int) ($student->active_points ?? 0));

                return $student;
            });
    }

    /**
     * Mengambil pilihan tahun ajaran untuk filter tampilan siswa.
     *
     * @return Collection<int, TahunAjaran>
     */
    public function academicYears(): Collection
    {
        return TahunAjaran::query()
            ->orderByDesc('status_aktif')
            ->orderByDesc('tanggal_mulai')
            ->get(['id', 'nama_tahun_ajaran', 'status_aktif']);
    }

    /**
     * Mengambil daftar kelas untuk pilihan filter.
     *
     * @return Collection<int, Kelas>
     */
    public function classes(): Collection
    {
        return Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get();
    }

    /**
     * Mengambil tingkat kelas unik yang tersedia.
     *
     * @return Collection<int, string>
     */
    public function levels(): Collection
    {
        return Kelas::query()->whereNotNull('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');
    }

    /**
     * Mengambil jurusan unik yang tersedia.
     *
     * @return Collection<int, string>
     */
    public function majors(): Collection
    {
        return Kelas::query()->whereNotNull('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
    }

    /**
     * Menggabungkan penempatan, catatan, dan rekap poin untuk rincian seorang siswa.
     *
     * @return array{placements: Collection, activePlacementIds: list<int>, violations: LengthAwarePaginator, pointRecap: Collection}
     */
    public function detail(Siswa $siswa): array
    {
        $placements = $siswa->penempatanKelas()
            ->with(['kelas:id,nama_kelas,tingkat,jurusan', 'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai'])
            ->withCount('catatanPelanggaran')
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id')
            ->get();
        $activePlacementIds = $placements
            ->filter(static fn (KelasSiswa $placement): bool => $placement->status_aktif && $placement->tanggal_selesai === null)
            ->modelKeys();

        $visibleViolations = $this->visibleViolations($siswa);

        $violations = (clone $visibleViolations)
            ->with([
                'penempatanKelas:id,siswa_id,kelas_id,tahun_ajaran_id',
                'penempatanKelas.kelas:id,nama_kelas',
                'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
                'pelanggaran:id,nama_pelanggaran',
                'guru:id,nama',
            ])
            ->select([
                'id',
                'kelas_siswa_id',
                'pelanggaran_id',
                'guru_id',
                'tanggal_pelanggaran',
                'poin_saat_dicatat',
                'status',
            ])
            ->whereHas('penempatanKelas', static fn (Builder $query): Builder => $query->where('siswa_id', $siswa->id))
            ->orderByDesc('tanggal_pelanggaran')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $pointsByAcademicYear = PelanggaranSiswa::query()
            ->join('t_kelas_siswa', 't_kelas_siswa.id', '=', 't_pelanggaran_siswa.kelas_siswa_id')
            ->join('t_tahun_ajaran', 't_tahun_ajaran.id', '=', 't_kelas_siswa.tahun_ajaran_id')
            ->where('t_kelas_siswa.siswa_id', $siswa->id)
            ->where('t_pelanggaran_siswa.status', 'aktif')
            ->whereIn('t_pelanggaran_siswa.id', (clone $visibleViolations)->select('t_pelanggaran_siswa.id'))
            ->select('t_tahun_ajaran.id', 't_tahun_ajaran.nama_tahun_ajaran')
            ->selectRaw('SUM(t_pelanggaran_siswa.poin_saat_dicatat) AS total_poin')
            ->groupBy('t_tahun_ajaran.id', 't_tahun_ajaran.nama_tahun_ajaran')
            ->get()
            ->keyBy('id');

        $pointRecap = $placements
            ->pluck('tahunAjaran')
            ->filter()
            ->unique('id')
            ->sortByDesc('tanggal_mulai')
            ->map(static fn ($year): array => [
                'academic_year' => $year,
                'total_points' => (int) ($pointsByAcademicYear->get($year->id)->total_poin ?? 0),
            ])
            ->values();

        return compact('placements', 'activePlacementIds', 'violations', 'pointRecap');
    }

    /**
     * Menyusun catatan pelanggaran yang boleh dilihat pengguna pada penempatan siswa.
     *
     * @return Builder<PelanggaranSiswa>
     */
    private function visibleViolations(Siswa $siswa): Builder
    {
        return PelanggaranSiswa::query()
            ->whereHas('penempatanKelas', static fn (Builder $query): Builder => $query->where('siswa_id', $siswa->id));
    }

    /**
     * Menentukan siswa yang rinciannya dapat diakses pengguna berdasarkan peran dan penugasan.
     *
     * @param  list<int>  $studentIds
     * @return list<int>
     */
    private function viewableStudentIds(User $user, array $studentIds): array
    {
        if ($user->role === 'admin') {
            return $studentIds;
        }

        if ($user->role !== 'guru' || $studentIds === []) {
            return [];
        }

        $teacherId = $user->guru()->where('status_aktif', true)->value('id');

        if ($teacherId === null) {
            return [];
        }

        $today = today()->toDateString();

        return Siswa::query()
            ->whereKey($studentIds)
            ->where('status_siswa', 'aktif')
            ->whereHas('penempatanKelas', static function (Builder $placementQuery) use ($teacherId, $today): void {
                $placementQuery
                    ->where('status_aktif', true)
                    ->whereDate('tanggal_mulai', '<=', $today)
                    ->where(static function (Builder $query) use ($today): void {
                        $query->whereNull('tanggal_selesai')
                            ->orWhereDate('tanggal_selesai', '>=', $today);
                    })
                    ->whereHas('tahunAjaran', static fn (Builder $yearQuery): Builder => $yearQuery
                        ->whereDate('tanggal_mulai', '<=', $today)
                        ->whereDate('tanggal_selesai', '>=', $today))
                    ->whereExists(static function (QueryBuilder $assignmentQuery) use ($teacherId, $today): void {
                        $assignmentQuery->selectRaw('1')
                            ->from('t_wali_kelas')
                            ->whereColumn('t_wali_kelas.kelas_id', 't_kelas_siswa.kelas_id')
                            ->whereColumn('t_wali_kelas.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                            ->where('t_wali_kelas.guru_id', $teacherId)
                            ->where('t_wali_kelas.status_aktif', true)
                            ->whereDate('t_wali_kelas.tanggal_mulai', '<=', $today)
                            ->where(static function (QueryBuilder $query) use ($today): void {
                                $query->whereNull('t_wali_kelas.tanggal_selesai')
                                    ->orWhereDate('t_wali_kelas.tanggal_selesai', '>=', $today);
                            });
                    });
            })
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->all();
    }
}
