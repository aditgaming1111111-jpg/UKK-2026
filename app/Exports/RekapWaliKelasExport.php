<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapWaliKelasExport implements Export, WithMultipleSheets
{
    /** @param array<string, mixed> $report */
    public function __construct(private readonly array $report) {}

    /** @return array<int, object> */
    public function sheets(): array
    {
        return [
            new RekapWaliKelasRingkasanSheet($this->report),
            new RekapWaliKelasRincianSheet($this->report['records']),
        ];
    }
}
