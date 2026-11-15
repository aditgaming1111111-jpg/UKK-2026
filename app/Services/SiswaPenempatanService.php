<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Memvalidasi dan mengelola riwayat penempatan siswa.
 */
class SiswaPenempatanService
{
    /**
     * Mengakhiri seluruh penempatan aktif tanpa menghapus riwayat siswa.
     */
    public function endActivePlacements(Siswa $student, CarbonImmutable $effectiveDate): int
    {
        $placements = $student->penempatanKelas()
            ->with('tahunAjaran:id,tanggal_mulai,tanggal_selesai')
            ->where('status_aktif', true)
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($placements as $placement) {
            $startDate = CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay();
            $endDate = $effectiveDate->startOfDay();

            if ($placement->tanggal_selesai !== null) {
                $endDate = CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()->min($endDate);
            }

            $academicYearEnd = $placement->tahunAjaran?->tanggal_selesai;

            if ($academicYearEnd !== null) {
                $yearEnd = CarbonImmutable::parse($academicYearEnd)->startOfDay();

                if ($yearEnd->gte($startDate)) {
                    $endDate = $endDate->min($yearEnd);
                }
            }

            $endDate = $endDate->max($startDate);

            $placement->update([
                'tanggal_selesai' => $endDate->toDateString(),
                'status_aktif' => false,
            ]);
        }

        return $placements->count();
    }

    /**
     * Menolak koreksi penempatan yang melanggar urutan dan rentang riwayat siswa.
     */
    public function assertCorrectionIsValid(
        KelasSiswa $placement,
        CarbonImmutable $startDate,
        ?CarbonImmutable $endDate,
    ): void {
        $academicYear = $placement->tahunAjaran;
        $yearStart = CarbonImmutable::parse($academicYear->tanggal_mulai)->startOfDay();
        $yearEnd = CarbonImmutable::parse($academicYear->tanggal_selesai)->startOfDay();

        if ($startDate->lt($yearStart) || $startDate->gt($yearEnd)
            || ($endDate !== null && ($endDate->lt($startDate) || $endDate->gt($yearEnd)))) {
            throw ValidationException::withMessages([
                'tanggal_mulai' => 'Tanggal penempatan harus berurutan dan berada dalam rentang tahun ajaran.',
            ]);
        }

        $overlappingPlacementQuery = $placement->siswa->penempatanKelas()
            ->whereKeyNot($placement->id)
            ->when($endDate !== null, static fn (Builder $query): Builder => $query->whereDate('tanggal_mulai', '<=', $endDate))
            ->where(static function (Builder $query) use ($startDate): void {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $startDate);
            });

        if ($overlappingPlacementQuery->lockForUpdate()->exists()) {
            throw ValidationException::withMessages([
                'tanggal_mulai' => 'Tanggal penempatan bertumpang tindih dengan riwayat penempatan siswa lainnya.',
            ]);
        }

        $hasViolationsOutsideCorrectedPeriod = $placement->catatanPelanggaran()
            ->whereDate('tanggal_pelanggaran', '<', $startDate->toDateString())
            ->exists();

        if (! $hasViolationsOutsideCorrectedPeriod && $endDate !== null) {
            $hasViolationsOutsideCorrectedPeriod = $placement->catatanPelanggaran()
                ->whereDate('tanggal_pelanggaran', '>', $endDate->toDateString())
                ->exists();
        }

        if ($hasViolationsOutsideCorrectedPeriod) {
            throw ValidationException::withMessages([
                'tanggal_mulai' => 'Rentang tanggal baru akan membuat tanggal catatan pelanggaran di luar periode penempatan.',
            ]);
        }
    }

    /**
     * Membuat penempatan siswa setelah memeriksa kelas, tahun ajaran, dan riwayat terkait.
     *
     * @param  array{kelas_id: int, tahun_ajaran_id: int, tanggal_mulai: string}  $attributes
     */
    public function assign(Siswa $siswa, array $attributes): KelasSiswa
    {
        return DB::transaction(function () use ($siswa, $attributes): KelasSiswa {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();
            $academicYear = TahunAjaran::query()->findOrFail($attributes['tahun_ajaran_id']);
            $startDate = CarbonImmutable::parse($attributes['tanggal_mulai'])->startOfDay();

            if ($startDate->lt($academicYear->tanggal_mulai) || $startDate->gt($academicYear->tanggal_selesai)) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Tanggal mulai harus berada dalam rentang tahun ajaran yang dipilih.',
                ]);
            }

            $placements = $lockedStudent->penempatanKelas()
                ->with('tahunAjaran:id,tanggal_selesai')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($placements->contains(static fn (KelasSiswa $placement): bool => $placement->status_aktif)) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Siswa sudah memiliki penempatan aktif. Gunakan tindakan Pindah Kelas untuk mengubah kelasnya.',
                ]);
            }

            foreach ($placements as $placement) {
                $placementStart = CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay();
                $placementEnd = $placement->tanggal_selesai
                    ? CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()
                    : null;
                $overlaps = $placementEnd === null || $placementEnd->gte($startDate);

                if (! $overlaps) {
                    continue;
                }

                if ($placement->status_aktif && $placementEnd === null && $placementStart->lt($startDate)) {
                    $endDate = $startDate->subDay();

                    if ($placement->tahunAjaran !== null && $endDate->gt($placement->tahunAjaran->tanggal_selesai)) {
                        $endDate = CarbonImmutable::parse($placement->tahunAjaran->tanggal_selesai);
                    }

                    $placement->update([
                        'tanggal_selesai' => $endDate->toDateString(),
                        'status_aktif' => false,
                    ]);

                    continue;
                }

                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Tanggal mulai bertumpang tindih dengan riwayat penempatan siswa.',
                ]);
            }

            return $lockedStudent->penempatanKelas()->create([
                'kelas_id' => $attributes['kelas_id'],
                'tahun_ajaran_id' => $attributes['tahun_ajaran_id'],
                'tanggal_mulai' => $startDate->toDateString(),
                'tanggal_selesai' => null,
                'status_aktif' => true,
            ]);
        });
    }

    /**
     * Memindahkan siswa ke kelas lain dalam tahun ajaran penempatan aktifnya.
     *
     * @param  array{kelas_id: int, tanggal_pindah: string, alasan: string}  $attributes
     * @return array{previous: KelasSiswa, current: KelasSiswa}
     */
    public function transfer(Siswa $student, array $attributes): array
    {
        return DB::transaction(function () use ($student, $attributes): array {
            $lockedStudent = Siswa::query()->whereKey($student->id)->lockForUpdate()->firstOrFail();

            if ($lockedStudent->status_siswa !== 'aktif') {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Hanya siswa berstatus Aktif yang dapat dipindahkan kelasnya.',
                ]);
            }

            $placements = $lockedStudent->penempatanKelas()
                ->with(['kelas:id,nama_kelas,tingkat,jurusan', 'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai'])
                ->where('status_aktif', true)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($placements->count() !== 1 || $placements->first()->tanggal_selesai !== null) {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Perpindahan memerlukan tepat satu penempatan aktif yang belum berakhir. Periksa riwayat penempatan siswa terlebih dahulu.',
                ]);
            }

            $previousPlacement = $placements->first();
            $targetClass = Kelas::query()->whereKey($attributes['kelas_id'])->lockForUpdate()->firstOrFail();

            if ($targetClass->id === $previousPlacement->kelas_id) {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Kelas tujuan harus berbeda dari kelas siswa saat ini.',
                ]);
            }

            $transferDate = CarbonImmutable::parse($attributes['tanggal_pindah'])->startOfDay();
            $academicYear = $previousPlacement->tahunAjaran;
            $placementStart = CarbonImmutable::parse($previousPlacement->tanggal_mulai)->startOfDay();
            $academicYearStart = CarbonImmutable::parse($academicYear->tanggal_mulai)->startOfDay();
            $academicYearEnd = CarbonImmutable::parse($academicYear->tanggal_selesai)->startOfDay();

            if ($transferDate->lte($placementStart) || $transferDate->lt($academicYearStart) || $transferDate->gt($academicYearEnd)) {
                throw ValidationException::withMessages([
                    'tanggal_pindah' => 'Tanggal pindah harus setelah tanggal mulai penempatan dan berada dalam rentang tahun ajaran saat ini.',
                ]);
            }

            $hasOverlappingPlacement = $lockedStudent->penempatanKelas()
                ->whereKeyNot($previousPlacement->id)
                ->where(static function (Builder $query) use ($transferDate): void {
                    $query->whereNull('tanggal_selesai')
                        ->orWhereDate('tanggal_selesai', '>=', $transferDate->toDateString());
                })
                ->lockForUpdate()
                ->exists();

            if ($hasOverlappingPlacement) {
                throw ValidationException::withMessages([
                    'tanggal_pindah' => 'Tanggal pindah bertumpang tindih dengan riwayat penempatan siswa lainnya.',
                ]);
            }

            if ($previousPlacement->catatanPelanggaran()
                ->whereDate('tanggal_pelanggaran', '>=', $transferDate->toDateString())
                ->exists()) {
                throw ValidationException::withMessages([
                    'tanggal_pindah' => 'Tanggal pindah tidak boleh mendahului catatan pelanggaran yang sudah tercatat pada kelas saat ini.',
                ]);
            }

            $previousPlacement->update([
                'tanggal_selesai' => $transferDate->subDay()->toDateString(),
                'status_aktif' => false,
            ]);

            $currentPlacement = $lockedStudent->penempatanKelas()->create([
                'kelas_id' => $targetClass->id,
                'tahun_ajaran_id' => $academicYear->id,
                'tanggal_mulai' => $transferDate->toDateString(),
                'tanggal_selesai' => null,
                'status_aktif' => true,
            ]);

            return [
                'previous' => $previousPlacement->fresh(['kelas', 'tahunAjaran']),
                'current' => $currentPlacement->load(['kelas', 'tahunAjaran']),
            ];
        });
    }
}
