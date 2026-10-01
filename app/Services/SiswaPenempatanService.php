<?php

namespace App\Services;

use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SiswaPenempatanService
{
    /**
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
}
