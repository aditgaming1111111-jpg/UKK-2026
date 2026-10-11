<?php

namespace App\Services;

use App\Models\PelanggaranSiswa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class LaporanPelanggaranService
{
    /** @param array<string, int|string|null> $filters
     * @return Builder<PelanggaranSiswa>
     */
    public function query(array $filters): Builder
    {
        $query = PelanggaranSiswa::query()
            ->with([
                'penempatanKelas.siswa:id,nisn,nama',
                'penempatanKelas.kelas:id,nama_kelas,tingkat,jurusan',
                'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
                'pelanggaran:id,kode,nama_pelanggaran',
                'guru:id,nama',
            ]);

        if (($filters['q'] ?? '') !== '') {
            $term = addcslashes(trim((string) $filters['q']), '%_\\');
            $query->whereHas('penempatanKelas.siswa', static fn (Builder $studentQuery): Builder => $studentQuery
                ->where('nisn', 'like', '%'.$term.'%')
                ->orWhere('nama', 'like', '%'.$term.'%'));
        }

        return $query
            ->when($filters['tahun_ajaran_id'] ?? null, static fn (Builder $builder, int|string $yearId): Builder => $builder
                ->whereHas('penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery->where('tahun_ajaran_id', $yearId)))
            ->when($filters['kelas_id'] ?? null, static fn (Builder $builder, int|string $classId): Builder => $builder
                ->whereHas('penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery->where('kelas_id', $classId)))
            ->when($filters['pelanggaran_id'] ?? null, static fn (Builder $builder, int|string $violationId): Builder => $builder->where('pelanggaran_id', $violationId))
            ->when($filters['tanggal_mulai'] ?? null, static fn (Builder $builder, string $date): Builder => $builder->whereDate('tanggal_pelanggaran', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, static fn (Builder $builder, string $date): Builder => $builder->whereDate('tanggal_pelanggaran', '<=', $date))
            ->when($filters['status'] ?? null, static fn (Builder $builder, string $status): Builder => $builder->where('status', $status))
            ->orderByDesc('tanggal_pelanggaran')
            ->orderByDesc('id');
    }

    /** @param Builder<PelanggaranSiswa> $query
     * @return array{active_records: int, active_points: int, cancelled_records: int}
     */
    public function summary(Builder $query): array
    {
        $aggregateQuery = clone $query->getQuery();
        $aggregateQuery->columns = null;
        $aggregateQuery->orders = null;
        $aggregateQuery->limit = null;
        $aggregateQuery->offset = null;

        $aggregate = $aggregateQuery
            ->selectRaw("SUM(CASE WHEN t_pelanggaran_siswa.status = 'aktif' THEN 1 ELSE 0 END) AS active_records")
            ->selectRaw("COALESCE(SUM(CASE WHEN t_pelanggaran_siswa.status = 'aktif' THEN t_pelanggaran_siswa.poin_saat_dicatat ELSE 0 END), 0) AS active_points")
            ->selectRaw("SUM(CASE WHEN t_pelanggaran_siswa.status = 'dibatalkan' THEN 1 ELSE 0 END) AS cancelled_records")
            ->first();

        return [
            'active_records' => (int) ($aggregate->active_records ?? 0),
            'active_points' => (int) ($aggregate->active_points ?? 0),
            'cancelled_records' => (int) ($aggregate->cancelled_records ?? 0),
        ];
    }

    /**
     * Constrain a report to notes that occurred during at least one of the
     * teacher's selected assignment periods.
     *
     * @param  array<int, int>  $assignmentIds
     */
    public function constrainNotesToAssignments(Builder $query, array $assignmentIds): Builder
    {
        return $query->whereExists(static function (QueryBuilder $assignmentQuery) use ($assignmentIds): void {
            $assignmentQuery->selectRaw('1')
                ->from('t_wali_kelas as report_assignment')
                ->whereIn('report_assignment.id', $assignmentIds)
                ->whereColumn('report_assignment.kelas_id', 't_kelas_siswa.kelas_id')
                ->whereColumn('report_assignment.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                ->whereColumn('report_assignment.tanggal_mulai', '<=', 't_pelanggaran_siswa.tanggal_pelanggaran')
                ->where(static function (QueryBuilder $periodQuery): void {
                    $periodQuery->whereNull('report_assignment.tanggal_selesai')
                        ->orWhereColumn('report_assignment.tanggal_selesai', '>=', 't_pelanggaran_siswa.tanggal_pelanggaran');
                });
        });
    }
}
