<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WaliKelasAssignmentService
{
    /**
     * @param  array{guru_id: int, kelas_id: int, tahun_ajaran_id: int, tanggal_mulai: string}  $attributes
     */
    public function assign(array $attributes): WaliKelas
    {
        return DB::transaction(function () use ($attributes): WaliKelas {
            $kelas = Kelas::query()->whereKey($attributes['kelas_id'])->lockForUpdate()->firstOrFail();
            $tahunAjaran = TahunAjaran::query()->findOrFail($attributes['tahun_ajaran_id']);
            $guru = Guru::query()->whereKey($attributes['guru_id'])->lockForUpdate()->firstOrFail();
            $startDate = CarbonImmutable::parse($attributes['tanggal_mulai'])->startOfDay();
            $yearStart = CarbonImmutable::parse($tahunAjaran->tanggal_mulai)->startOfDay();
            $yearEnd = CarbonImmutable::parse($tahunAjaran->tanggal_selesai)->startOfDay();

            if (! $guru->status_aktif) {
                throw ValidationException::withMessages(['guru_id' => 'Guru nonaktif tidak dapat menerima penugasan baru.']);
            }

            if ($startDate->lt($yearStart) || $startDate->gt($yearEnd)) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Tanggal mulai harus berada dalam periode tahun ajaran yang dipilih.',
                ]);
            }

            $assignments = WaliKelas::query()
                ->with('tahunAjaran:id,tanggal_selesai')
                ->where('kelas_id', $kelas->id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            foreach ($assignments as $assignment) {
                $assignmentStart = CarbonImmutable::parse($assignment->tanggal_mulai)->startOfDay();
                $assignmentEnd = $assignment->tanggal_selesai
                    ? CarbonImmutable::parse($assignment->tanggal_selesai)->startOfDay()
                    : CarbonImmutable::parse($assignment->tahunAjaran->tanggal_selesai)->startOfDay();
                $overlaps = $assignmentStart->lte($yearEnd) && $assignmentEnd->gte($startDate);

                if ($assignment->status_aktif && $assignment->tanggal_selesai === null && $assignmentEnd->lt($startDate)) {
                    $assignment->update([
                        'tanggal_selesai' => $assignmentEnd->toDateString(),
                        'status_aktif' => false,
                    ]);

                    continue;
                }

                if (! $overlaps) {
                    continue;
                }

                if ($assignment->status_aktif && $assignment->tanggal_selesai === null && $assignmentStart->lt($startDate)) {
                    $endDate = $startDate->subDay();
                    $previousYearEnd = CarbonImmutable::parse($assignment->tahunAjaran->tanggal_selesai)->startOfDay();

                    if ($endDate->gt($previousYearEnd)) {
                        $endDate = $previousYearEnd;
                    }

                    if ($endDate->lt($assignmentStart)) {
                        throw ValidationException::withMessages([
                            'tanggal_mulai' => 'Tanggal mulai baru bertentangan dengan periode penugasan sebelumnya.',
                        ]);
                    }

                    $assignment->update([
                        'tanggal_selesai' => $endDate->toDateString(),
                        'status_aktif' => false,
                    ]);

                    continue;
                }

                throw ValidationException::withMessages([
                    'kelas_id' => 'Kelas sudah memiliki penugasan wali kelas yang bertumpang tindih pada periode tersebut.',
                ]);
            }

            return WaliKelas::query()->create([
                'guru_id' => $guru->id,
                'kelas_id' => $kelas->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'tanggal_mulai' => $startDate->toDateString(),
                'tanggal_selesai' => null,
                'status_aktif' => true,
            ]);
        });
    }

    public function close(WaliKelas $waliKelas, string $endDate): WaliKelas
    {
        return DB::transaction(function () use ($waliKelas, $endDate): WaliKelas {
            Kelas::query()->whereKey($waliKelas->kelas_id)->lockForUpdate()->firstOrFail();
            $assignment = WaliKelas::query()->whereKey($waliKelas->id)->lockForUpdate()->firstOrFail();
            $tahunAjaran = TahunAjaran::query()->findOrFail($assignment->tahun_ajaran_id);
            $start = CarbonImmutable::parse($assignment->tanggal_mulai)->startOfDay();
            $end = CarbonImmutable::parse($endDate)->startOfDay();
            $yearEnd = CarbonImmutable::parse($tahunAjaran->tanggal_selesai)->startOfDay();

            if (! $assignment->status_aktif || $assignment->tanggal_selesai !== null) {
                throw ValidationException::withMessages(['tanggal_selesai' => 'Penugasan ini sudah berakhir.']);
            }

            if ($end->lt($start) || $end->gt($yearEnd)) {
                throw ValidationException::withMessages([
                    'tanggal_selesai' => 'Tanggal selesai harus berada setelah tanggal mulai dan dalam periode tahun ajaran.',
                ]);
            }

            $assignment->update([
                'tanggal_selesai' => $end->toDateString(),
                'status_aktif' => false,
            ]);

            return $assignment->refresh();
        });
    }
}
