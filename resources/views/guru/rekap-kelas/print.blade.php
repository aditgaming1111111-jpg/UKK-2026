<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Wali Kelas {{ $selectedClass->nama_kelas }}</title>
    <style>
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        h2 { font-size: 13px; margin: 18px 0 8px; }
        p { margin: 4px 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; }
        .summary { width: auto; margin: 12px 0; }
        .muted { color: #4b5563; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <h1>Rekap Pelanggaran Kelas</h1>
    <p><strong>Kelas:</strong> {{ $selectedClass->nama_kelas }} · <strong>Tahun ajaran:</strong> {{ $selectedYear->nama_tahun_ajaran }}</p>
    <p><strong>Wali kelas:</strong> {{ $teacher->nama }}</p>
    <p><strong>Filter siswa:</strong> {{ $filters['q'] ?? 'Semua siswa' }} · <strong>Rentang kejadian:</strong> {{ $filters['tanggal_mulai'] ?? 'Awal periode' }} s.d. {{ $filters['tanggal_selesai'] ?? 'Akhir periode' }}</p>
    <p class="muted">Dicetak pada {{ $generatedAt->format('d/m/Y H:i') }}</p>

    <table class="summary">
        <thead><tr><th>Jumlah siswa</th><th>Siswa dengan pelanggaran aktif</th><th>Catatan aktif</th><th>Total poin aktif</th></tr></thead>
        <tbody><tr><td>{{ $summary['student_count'] }}</td><td>{{ $summary['students_with_active_records'] }}</td><td>{{ $summary['active_records'] }}</td><td>{{ $summary['active_points'] }}</td></tr></tbody>
    </table>

    <h2>Ringkasan per siswa</h2>
    <table>
        <thead><tr><th>NISN</th><th>Nama siswa</th><th>Catatan aktif</th><th>Poin aktif</th></tr></thead>
        <tbody>
            @forelse ($students as $student)
                <tr><td>{{ $student->nisn }}</td><td>{{ $student->nama }}</td><td>{{ $student->active_records }}</td><td>{{ $student->active_points }}</td></tr>
            @empty
                <tr><td colspan="4">Tidak ada siswa pada filter yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Rincian kejadian</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>Siswa / NISN</th><th>Kelas</th><th>Jenis / kategori</th><th>Poin snapshot</th><th>Guru pencatat</th><th>Keterangan / tindakan</th><th>Status / alasan</th></tr></thead>
        <tbody>
            @forelse ($records as $record)
                @php($auditSummary = $record->logPerubahan->map(fn ($log) => ucfirst($log->aksi) . ' oleh ' . ($log->pengguna?->nama ?? 'Pengguna tidak tersedia') . ' pada ' . ($log->created_at?->format('d/m/Y H:i') ?? 'waktu tidak tersedia') . ($log->alasan ? ': ' . $log->alasan : ''))->implode(' | '))
                <tr>
                    <td class="nowrap">{{ $record->tanggal_pelanggaran?->format('d/m/Y') }}</td>
                    <td>{{ $record->penempatanKelas?->siswa?->nama }}<br>{{ $record->penempatanKelas?->siswa?->nisn }}</td>
                    <td>{{ $record->penempatanKelas?->kelas?->nama_kelas }}</td>
                    <td>{{ $record->pelanggaran?->nama_pelanggaran }}<br><span class="muted">{{ $record->pelanggaran?->kategori?->nama_kategori }}</span></td>
                    <td>{{ $record->poin_saat_dicatat }}</td>
                    <td>{{ $record->guru?->nama }}</td>
                    <td>{{ $record->keterangan ?: '—' }}<br><span class="muted">{{ $record->tindakan ?: '—' }}</span></td>
                    <td>{{ ucfirst($record->status) }}@if ($auditSummary !== '')<br><span class="muted">{{ $auditSummary }}</span>@endif</td>
                </tr>
            @empty
                <tr><td colspan="8">Tidak ada catatan dalam rentang filter yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
