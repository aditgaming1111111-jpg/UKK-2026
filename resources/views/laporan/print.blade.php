<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 10px; }
        h1 { margin: 0 0 5px; font-size: 19px; }
        h2 { margin: 18px 0 6px; font-size: 13px; }
        p { margin: 3px 0; }
        .muted { color: #555; }
        .summary { margin: 14px 0; }
        .summary td { border: 1px solid #aaa; padding: 7px; width: 33%; }
        .filters { width: 100%; border-collapse: collapse; }
        .filters td { padding: 3px 8px 3px 0; vertical-align: top; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 9px; }
        .data th, .data td { border: 1px solid #888; padding: 5px; text-align: left; }
        .data th { background: #eee; }
        .toolbar { margin-bottom: 12px; }
        @media print { .toolbar { display: none; } }
    </style>
</head>
<body>
    <div class="toolbar"><button onclick="window.print()">Cetak laporan</button></div>
    <h1>{{ $reportTitle }}</h1>
    <p><strong>Periode:</strong> {{ $selectedYear?->nama_tahun_ajaran ?? 'Semua tahun ajaran' }}
        @if ($selectedYear) ({{ $selectedYear->tanggal_mulai->format('d/m/Y') }} – {{ $selectedYear->tanggal_selesai->format('d/m/Y') }}) @endif
    </p>
    <p class="muted">Dicetak pada {{ $generatedAt->format('d/m/Y H:i:s') }}</p>
    <h2>Filter laporan</h2>
    <table class="filters"><tbody>@foreach ($filterLabels as $label => $value)<tr><td><strong>{{ $label }}</strong></td><td>{{ $value }}</td></tr>@endforeach</tbody></table>
    <table class="summary"><tr><td><strong>Catatan aktif:</strong> {{ $summary['active_records'] }}</td><td><strong>Total poin aktif:</strong> {{ $summary['active_points'] }}</td><td><strong>Catatan dibatalkan:</strong> {{ $summary['cancelled_records'] }}</td></tr></table>
    <h2>Detail catatan pelanggaran</h2>
    @if ($records->isEmpty())
        <p class="muted">Tidak ada catatan sesuai filter.</p>
    @else
        <table class="data"><thead><tr><th>Tanggal kejadian</th><th>Siswa / NISN</th><th>Kelas saat kejadian</th><th>Tahun ajaran</th><th>Jenis pelanggaran</th><th>Guru pencatat</th><th>Poin snapshot</th><th>Status</th></tr></thead>
            <tbody>@foreach ($records as $record)<tr><td>{{ $record->tanggal_pelanggaran->format('d/m/Y') }}</td><td>{{ $record->penempatanKelas?->siswa?->nama ?? '—' }}<br>{{ $record->penempatanKelas?->siswa?->nisn ?? '—' }}</td><td>{{ $record->penempatanKelas?->kelas?->nama_kelas ?? '—' }}</td><td>{{ $record->penempatanKelas?->tahunAjaran?->nama_tahun_ajaran ?? '—' }}</td><td>{{ $record->pelanggaran?->nama_pelanggaran ?? '—' }}</td><td>{{ $record->guru?->nama ?? '—' }}</td><td>{{ $record->poin_saat_dicatat }}</td><td>{{ $record->status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}</td></tr>@endforeach</tbody>
        </table>
    @endif
</body>
</html>
