<?php

namespace App\Services;

use App\Models\LogPelanggaran;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class RiwayatPelanggaranService
{
    /** @param array{pelanggaran_id: int, tanggal_pelanggaran: string, keterangan?: ?string, tindakan?: ?string, alasan: string} $attributes */
    public function update(User $user, PelanggaranSiswa $record, array $attributes): PelanggaranSiswa
    {
        return DB::transaction(function () use ($user, $record, $attributes): PelanggaranSiswa {
            $lockedRecord = PelanggaranSiswa::query()->whereKey($record->id)->lockForUpdate()->firstOrFail();
            Gate::authorize('update', $lockedRecord);

            $before = $lockedRecord->getAttributes();
            $selectedViolationId = (int) $attributes['pelanggaran_id'];
            $violationChanged = $selectedViolationId !== (int) $lockedRecord->pelanggaran_id;
            $violation = null;

            if ($violationChanged) {
                $violation = Pelanggaran::availableForRecording()
                    ->whereKey($selectedViolationId)
                    ->lockForUpdate()
                    ->first();

                if ($violation === null) {
                    throw ValidationException::withMessages(['pelanggaran_id' => 'Jenis pengganti sudah tidak aktif. Muat ulang formulir.']);
                }
            }

            $this->ensureIncidentDateIsValid($lockedRecord, $attributes['tanggal_pelanggaran']);

            $lockedRecord->update([
                'pelanggaran_id' => $selectedViolationId,
                'tanggal_pelanggaran' => $attributes['tanggal_pelanggaran'],
                'poin_saat_dicatat' => $violationChanged ? $violation->poin : $lockedRecord->poin_saat_dicatat,
                'keterangan' => $attributes['keterangan'] ?? null,
                'tindakan' => $attributes['tindakan'] ?? null,
            ]);

            $this->writeLog($lockedRecord, $user, 'edit', $before, $lockedRecord->getAttributes(), $attributes['alasan']);

            return $lockedRecord->fresh()->load($this->detailRelations());
        }, 3);
    }

    public function cancel(User $user, PelanggaranSiswa $record, string $reason): PelanggaranSiswa
    {
        return DB::transaction(function () use ($user, $record, $reason): PelanggaranSiswa {
            $lockedRecord = PelanggaranSiswa::query()->whereKey($record->id)->lockForUpdate()->firstOrFail();
            Gate::authorize('cancel', $lockedRecord);

            if ($lockedRecord->status !== 'aktif') {
                throw ValidationException::withMessages(['catatan' => 'Catatan ini sudah dibatalkan.']);
            }

            $before = $lockedRecord->getAttributes();
            $lockedRecord->update(['status' => 'dibatalkan']);
            $this->writeLog($lockedRecord, $user, 'batal', $before, $lockedRecord->getAttributes(), $reason);

            return $lockedRecord->fresh()->load($this->detailRelations());
        }, 3);
    }

    /** @return array<int, string> */
    public function detailRelations(): array
    {
        return [
            'penempatanKelas.siswa:id,nisn,nama',
            'penempatanKelas.kelas:id,nama_kelas,tingkat,jurusan',
            'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai',
            'pelanggaran:id,kategori_id,kode,nama_pelanggaran,poin,status_aktif',
            'pelanggaran.kategori:id,nama_kategori,status_aktif',
            'guru:id,nama',
            'logPerubahan.pengguna:id,nama',
        ];
    }

    private function ensureIncidentDateIsValid(PelanggaranSiswa $record, string $date): void
    {
        $placement = $record->penempatanKelas()->with('tahunAjaran')->lockForUpdate()->firstOrFail();
        $year = $placement->tahunAjaran;
        $incidentDate = CarbonImmutable::parse($date)->startOfDay();
        $placementStart = CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay();
        $placementEnd = $placement->tanggal_selesai !== null
            ? CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()
            : CarbonImmutable::parse($year->tanggal_selesai)->startOfDay();
        $yearStart = CarbonImmutable::parse($year->tanggal_mulai)->startOfDay();
        $yearEnd = CarbonImmutable::parse($year->tanggal_selesai)->startOfDay();

        if ($incidentDate->lt($placementStart) || $incidentDate->gt($placementEnd)
            || $incidentDate->lt($yearStart) || $incidentDate->gt($yearEnd)) {
            throw ValidationException::withMessages([
                'tanggal_pelanggaran' => 'Tanggal kejadian harus berada pada periode kelas dan tahun ajaran catatan.',
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     */
    private function writeLog(
        PelanggaranSiswa $record,
        User $user,
        string $action,
        array $before,
        array $after,
        string $reason,
    ): void {
        LogPelanggaran::query()->create([
            'pelanggaran_siswa_id' => $record->id,
            'user_id' => $user->id,
            'aksi' => $action,
            'data_sebelum' => json_encode($before, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'data_sesudah' => json_encode($after, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'alasan' => $reason,
        ]);
    }
}
