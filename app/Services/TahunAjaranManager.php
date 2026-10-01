<?php

namespace App\Services;

use App\Models\TahunAjaran;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TahunAjaranManager
{
    /**
     * @param  array{nama_tahun_ajaran: string, tanggal_mulai: string, tanggal_selesai: string, status_aktif: bool}  $attributes
     */
    public function create(array $attributes): TahunAjaran
    {
        return DB::transaction(function () use ($attributes): TahunAjaran {
            $years = TahunAjaran::query()->orderBy('id')->lockForUpdate()->get();
            $this->ensureUniqueName($years, $attributes['nama_tahun_ajaran']);
            $this->ensurePeriodAvailable($years, $attributes['tanggal_mulai'], $attributes['tanggal_selesai']);

            if ($attributes['status_aktif']) {
                TahunAjaran::query()->where('status_aktif', true)->update(['status_aktif' => false]);
            }

            return TahunAjaran::query()->create($attributes);
        });
    }

    /**
     * @param  array{nama_tahun_ajaran: string, tanggal_mulai: string, tanggal_selesai: string, status_aktif: bool}  $attributes
     */
    public function update(TahunAjaran $tahunAjaran, array $attributes): TahunAjaran
    {
        return DB::transaction(function () use ($tahunAjaran, $attributes): TahunAjaran {
            $years = TahunAjaran::query()->orderBy('id')->lockForUpdate()->get();
            $lockedYear = $years->firstWhere('id', $tahunAjaran->id);

            if (! $lockedYear instanceof TahunAjaran) {
                throw (new ModelNotFoundException)->setModel(TahunAjaran::class, [$tahunAjaran->id]);
            }

            $this->ensureUniqueName($years, $attributes['nama_tahun_ajaran'], $lockedYear->id);
            $this->ensurePeriodAvailable($years, $attributes['tanggal_mulai'], $attributes['tanggal_selesai'], $lockedYear->id);
            $this->ensurePeriodContainsHistory($lockedYear, $attributes['tanggal_mulai'], $attributes['tanggal_selesai']);

            if ($attributes['status_aktif']) {
                TahunAjaran::query()
                    ->where('status_aktif', true)
                    ->where('id', '!=', $lockedYear->id)
                    ->update(['status_aktif' => false]);
            }

            $lockedYear->update($attributes);

            return $lockedYear->refresh();
        });
    }

    /**
     * @param  Collection<int, TahunAjaran>  $years
     */
    private function ensureUniqueName(Collection $years, string $name, ?int $exceptId = null): void
    {
        $exists = $years->contains(static fn (TahunAjaran $year): bool => $year->id !== $exceptId
            && mb_strtolower($year->nama_tahun_ajaran) === mb_strtolower($name));

        if ($exists) {
            throw ValidationException::withMessages([
                'nama_tahun_ajaran' => 'Nama tahun ajaran sudah digunakan.',
            ]);
        }
    }

    /**
     * @param  Collection<int, TahunAjaran>  $years
     */
    private function ensurePeriodAvailable(
        Collection $years,
        string $startDate,
        string $endDate,
        ?int $exceptId = null,
    ): void {
        $start = CarbonImmutable::parse($startDate)->startOfDay();
        $end = CarbonImmutable::parse($endDate)->startOfDay();

        $conflicts = $years->contains(static function (TahunAjaran $year) use ($start, $end, $exceptId): bool {
            if ($year->id === $exceptId) {
                return false;
            }

            return CarbonImmutable::parse($year->tanggal_mulai)->lte($end)
                && CarbonImmutable::parse($year->tanggal_selesai)->gte($start);
        });

        if ($conflicts) {
            throw ValidationException::withMessages([
                'tanggal_mulai' => 'Periode tahun ajaran bertumpang tindih dengan tahun ajaran lain.',
            ]);
        }
    }

    private function ensurePeriodContainsHistory(TahunAjaran $tahunAjaran, string $startDate, string $endDate): void
    {
        $start = CarbonImmutable::parse($startDate)->toDateString();
        $end = CarbonImmutable::parse($endDate)->toDateString();
        $hasOutOfPeriodPlacement = $tahunAjaran->penempatanSiswa()
            ->where(static function ($query) use ($start, $end): void {
                $query->whereDate('tanggal_mulai', '<', $start)
                    ->orWhereDate('tanggal_mulai', '>', $end)
                    ->orWhere(static function ($endDateQuery) use ($end): void {
                        $endDateQuery->whereNotNull('tanggal_selesai')->whereDate('tanggal_selesai', '>', $end);
                    });
            })
            ->exists();
        $hasOutOfPeriodAssignment = $tahunAjaran->penugasanWaliKelas()
            ->where(static function ($query) use ($start, $end): void {
                $query->whereDate('tanggal_mulai', '<', $start)
                    ->orWhereDate('tanggal_mulai', '>', $end)
                    ->orWhere(static function ($endDateQuery) use ($end): void {
                        $endDateQuery->whereNotNull('tanggal_selesai')->whereDate('tanggal_selesai', '>', $end);
                    });
            })
            ->exists();

        if ($hasOutOfPeriodPlacement || $hasOutOfPeriodAssignment) {
            throw ValidationException::withMessages([
                'tanggal_mulai' => 'Periode tidak dapat diubah karena akan membuat riwayat penempatan atau penugasan berada di luar tahun ajaran.',
            ]);
        }
    }
}
