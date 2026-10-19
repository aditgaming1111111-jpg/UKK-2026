<?php

namespace App\Exports;

use App\Models\PelanggaranSiswa;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapWaliKelasRincianSheet implements FromQuery, WithCustomChunkSize, WithHeadings, WithMapping, WithTitle
{
    /** @param Builder<PelanggaranSiswa> $records */
    public function __construct(private readonly Builder $records) {}

    /** @return Builder<PelanggaranSiswa> */
    public function query(): Builder
    {
        return $this->records;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map(mixed $record): array
    {
        assert($record instanceof PelanggaranSiswa);

        $auditSummary = $record->logPerubahan
            ->map(static fn ($log): string => trim(ucfirst($log->aksi).' oleh '.($log->pengguna?->nama ?? 'Pengguna tidak tersedia')
                .' pada '.($log->created_at?->format('d/m/Y H:i') ?? 'waktu tidak tersedia')
                .($log->alasan ? ': '.$log->alasan : '')))
            ->implode(' | ');

        return [
            self::safeText($record->penempatanKelas?->tahunAjaran?->nama_tahun_ajaran),
            self::safeText($record->penempatanKelas?->kelas?->nama_kelas),
            self::safeText($record->penempatanKelas?->siswa?->nisn),
            self::safeText($record->penempatanKelas?->siswa?->nama),
            self::safeText($record->tanggal_pelanggaran?->format('d/m/Y')),
            self::safeText($record->pelanggaran?->kategori?->nama_kategori),
            self::safeText($record->pelanggaran?->nama_pelanggaran),
            $record->poin_saat_dicatat,
            self::safeText($record->guru?->nama),
            self::safeText($record->keterangan),
            self::safeText($record->tindakan),
            self::safeText($record->status),
            self::safeText($auditSummary),
        ];
    }

    public function headings(): array
    {
        return ['Tahun ajaran', 'Kelas saat kejadian', 'NISN', 'Nama siswa', 'Tanggal kejadian', 'Kategori', 'Jenis pelanggaran', 'Poin saat dicatat', 'Guru pencatat', 'Keterangan', 'Tindakan', 'Status', 'Riwayat perubahan'];
    }

    public function title(): string
    {
        return 'Rincian Kejadian';
    }

    private static function safeText(?string $value): string
    {
        $value ??= '';

        return preg_match('/^[=+\-@\t\r]/u', $value) === 1 ? "'".$value : $value;
    }
}
