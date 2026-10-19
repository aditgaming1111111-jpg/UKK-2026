<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapWaliKelasRingkasanSheet implements FromCollection, WithHeadings, WithTitle
{
    /** @param array<string, mixed> $report */
    public function __construct(private readonly array $report) {}

    public function collection(): Collection
    {
        $year = $this->report['selectedYear']->nama_tahun_ajaran;
        $class = $this->report['selectedClass']->nama_kelas;
        $teacher = $this->report['teacher']->nama;
        $summary = $this->report['summary'];
        $filters = $this->report['filters'];
        $printedAt = $this->report['generatedAt']->format('d/m/Y H:i');

        return $this->report['students']->map(static fn ($student): array => [
            self::safeText($year),
            self::safeText($class),
            self::safeText($teacher),
            self::safeText($student->nisn),
            self::safeText($student->nama),
            $student->active_records,
            $student->active_points,
            $summary['student_count'],
            $summary['students_with_active_records'],
            $summary['active_records'],
            $summary['active_points'],
            self::safeText($filters['q'] ?? 'Semua siswa'),
            self::safeText($filters['tanggal_mulai'] ?? 'Awal periode'),
            self::safeText($filters['tanggal_selesai'] ?? 'Akhir periode'),
            self::safeText($printedAt),
        ]);
    }

    public function headings(): array
    {
        return ['Tahun ajaran', 'Kelas', 'Wali kelas', 'NISN', 'Nama siswa', 'Catatan aktif siswa', 'Poin aktif siswa', 'Jumlah siswa kelas', 'Siswa dengan catatan aktif', 'Total catatan aktif kelas', 'Total poin aktif kelas', 'Filter nama/NISN', 'Tanggal kejadian dari', 'Tanggal kejadian sampai', 'Waktu ekspor'];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    private static function safeText(?string $value): string
    {
        $value ??= '';

        return preg_match('/^[=+\-@\t\r]/u', $value) === 1 ? "'".$value : $value;
    }
}
