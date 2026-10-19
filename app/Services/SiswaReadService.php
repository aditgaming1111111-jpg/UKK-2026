<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

class SiswaReadService
{
    /**
     * @param  array{q?: string|null, kelas_id?: int|string|null, tingkat?: string|null, jurusan?: string|null, status?: string|null}  $filters
     * @return LengthAwarePaginator<int, Siswa>
     */
    public function directory(array $filters): LengthAwarePaginator
    {
        $classId = $filters['kelas_id'] ?? null;
        $level = $filters['tingkat'] ?? null;
        $major = $filters['jurusan'] ?? null;

        return Siswa::query()
            ->select(['id', 'nisn', 'nama', 'jenis_kelamin', 'status_siswa'])
            ->with(['penempatanKelas' => static fn (Relation $query): Relation => $query
                ->select(['id', 'siswa_id', 'kelas_id', 'status_aktif', 'tanggal_selesai'])
                ->where('status_aktif', true)
                ->whereNull('tanggal_selesai')
                ->with('kelas:id,nama_kelas,tingkat,jurusan')])
            ->when($filters['q'] ?? null, static fn (Builder $query, string $search): Builder => $query
                ->where(static function (Builder $searchQuery) use ($search): void {
                    $searchQuery->where('nisn', 'like', '%'.$search.'%')
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
    }

    /** @return Collection<int, Kelas> */
    public function classes(): Collection
    {
        return Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get();
    }

    /** @return Collection<int, string> */
    public function levels(): Collection
    {
        return Kelas::query()->whereNotNull('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');
    }

    /** @return Collection<int, string> */
    public function majors(): Collection
    {
        return Kelas::query()->whereNotNull('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
    }

    /**
     * @return array{placements: Collection, violations: LengthAwarePaginator, pointRecap: Collection}
     */
    public function detail(Siswa $siswa, ?Guru $guru = null): array
    {
        $placements = $siswa->penempatanKelas()
            ->with(['kelas:id,nama_kelas,tingkat,jurusan', 'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai'])
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id')
            ->get();

        $visibleViolations = $this->visibleViolations($siswa, $guru);

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

        return compact('placements', 'violations', 'pointRecap');
    }

    /**
     * @return Builder<PelanggaranSiswa>
     */
    private function visibleViolations(Siswa $siswa, ?Guru $guru): Builder
    {
        return PelanggaranSiswa::query()
            ->whereHas('penempatanKelas', static fn (Builder $query): Builder => $query->where('siswa_id', $siswa->id))
            ->when($guru !== null, static fn (Builder $query): Builder => $query->where(static function (Builder $visibilityQuery) use ($guru, $siswa): void {
                $visibilityQuery->where('guru_id', $guru->id)
                    ->orWhereHas('penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery
                        ->where('siswa_id', $siswa->id)
                        ->whereExists(static fn (QueryBuilder $assignmentQuery): QueryBuilder => $assignmentQuery
                            ->selectRaw('1')
                            ->from('t_wali_kelas')
                            ->whereColumn('t_wali_kelas.kelas_id', 't_kelas_siswa.kelas_id')
                            ->whereColumn('t_wali_kelas.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                            ->where('t_wali_kelas.guru_id', $guru->id)
                            ->where('t_wali_kelas.status_aktif', true)
                            ->whereColumn('t_wali_kelas.tanggal_mulai', '<=', 't_pelanggaran_siswa.tanggal_pelanggaran')
                            ->where(static function (QueryBuilder $dateQuery): void {
                                $dateQuery->whereNull('t_wali_kelas.tanggal_selesai')
                                    ->orWhereColumn('t_wali_kelas.tanggal_selesai', '>=', 't_pelanggaran_siswa.tanggal_pelanggaran');
                            })));
            }));
    }
}
