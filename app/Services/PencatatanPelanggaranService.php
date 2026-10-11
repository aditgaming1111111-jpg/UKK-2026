<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\KelasSiswa;
use App\Models\LogPelanggaran;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PencatatanPelanggaranService
{
    /** @param array{kelas_siswa_id: int, pelanggaran_id: int, tanggal_pelanggaran: string, keterangan?: ?string, tindakan?: ?string} $attributes */
    public function record(User $user, array $attributes): PelanggaranSiswa
    {
        return DB::transaction(function () use ($user, $attributes): PelanggaranSiswa {
            $guru = Guru::query()
                ->where('user_id', $user->id)
                ->where('status_aktif', true)
                ->lockForUpdate()
                ->first();

            if ($guru === null) {
                throw ValidationException::withMessages([
                    'akun' => 'Akun Guru aktif tidak memiliki identitas Guru yang valid.',
                ]);
            }

            $placement = KelasSiswa::query()
                ->with('tahunAjaran')
                ->whereKey($attributes['kelas_siswa_id'])
                ->lockForUpdate()
                ->first();

            if ($placement === null || $placement->tahunAjaran === null) {
                throw ValidationException::withMessages([
                    'kelas_siswa_id' => 'Penempatan kelas siswa tidak ditemukan.',
                ]);
            }

            $incidentDate = CarbonImmutable::parse($attributes['tanggal_pelanggaran'])->startOfDay();
            $placementStart = CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay();
            $yearStart = CarbonImmutable::parse($placement->tahunAjaran->tanggal_mulai)->startOfDay();
            $yearEnd = CarbonImmutable::parse($placement->tahunAjaran->tanggal_selesai)->startOfDay();
            $placementEnd = $placement->tanggal_selesai !== null
                ? CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()
                : $yearEnd;

            if ($incidentDate->lt($placementStart) || $incidentDate->gt($placementEnd)
                || $incidentDate->lt($yearStart) || $incidentDate->gt($yearEnd)) {
                throw ValidationException::withMessages([
                    'kelas_siswa_id' => 'Penempatan kelas dan tahun ajaran tidak berlaku pada tanggal kejadian.',
                ]);
            }

            $violation = Pelanggaran::availableForRecording()
                ->whereKey($attributes['pelanggaran_id'])
                ->lockForUpdate()
                ->first();

            if ($violation === null) {
                throw ValidationException::withMessages([
                    'pelanggaran_id' => 'Jenis pelanggaran tidak ditemukan atau sudah nonaktif.',
                ]);
            }

            $record = PelanggaranSiswa::query()->create([
                'kelas_siswa_id' => $placement->id,
                'pelanggaran_id' => $violation->id,
                'guru_id' => $guru->id,
                'tanggal_pelanggaran' => $incidentDate->toDateString(),
                'tanggal_dicatat' => now(),
                'poin_saat_dicatat' => $violation->poin,
                'keterangan' => $attributes['keterangan'] ?? null,
                'tindakan' => $attributes['tindakan'] ?? null,
                'status' => 'aktif',
            ]);

            LogPelanggaran::query()->create([
                'pelanggaran_siswa_id' => $record->id,
                'user_id' => $user->id,
                'aksi' => 'tambah',
                'data_sebelum' => null,
                'data_sesudah' => json_encode($record->getAttributes(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'alasan' => 'Pencatatan awal',
            ]);

            return $record->load([
                'penempatanKelas.siswa',
                'pelanggaran:id,kode,nama_pelanggaran',
                'guru:id,nama',
            ]);
        }, 3);
    }
}
