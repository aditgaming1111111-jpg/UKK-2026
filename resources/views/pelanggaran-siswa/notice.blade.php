<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Pemberitahuan Pelanggaran</title>
    <style>
        @page { margin: 18mm 20mm; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.55; }
        h1 { margin: 16px 0 4px; font-size: 16px; text-align: center; text-decoration: underline; text-transform: uppercase; }
        p { margin: 8px 0; }
        .date { margin: 10px 0 18px; text-align: right; }
        .identity, .signatures { width: 100%; border-collapse: collapse; }
        .identity td { padding: 4px 6px; vertical-align: top; }
        .identity td:first-child { width: 185px; }
        .signatures { margin-top: 36px; page-break-inside: avoid; }
        .signatures td { width: 50%; height: 95px; text-align: center; vertical-align: top; }
        .signatures .name { padding-top: 62px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <x-documents.school-letterhead />

    <h1>Surat Pemberitahuan Pelanggaran</h1>
    <p class="date">{{ $generatedAt->locale('id')->translatedFormat('d F Y') }}</p>

    <p>Kepada Yth.<br><strong>Orang Tua/Wali dari {{ $record->penempatanKelas?->siswa?->nama ?? 'Siswa' }}</strong><br>di tempat</p>
    <p>Dengan hormat,</p>
    <p>Kami memberitahukan bahwa siswa tersebut tercatat melakukan pelanggaran tata tertib sekolah dengan rincian berikut:</p>

    <table class="identity">
        <tbody>
            <tr><td>Nama siswa</td><td>: {{ $record->penempatanKelas?->siswa?->nama ?? '—' }}</td></tr>
            @if ($record->penempatanKelas?->siswa?->nis)
                <tr><td>NIS</td><td>: {{ $record->penempatanKelas->siswa->nis }}</td></tr>
            @endif
            @if ($record->penempatanKelas?->siswa?->nisn)
                <tr><td>NISN</td><td>: {{ $record->penempatanKelas->siswa->nisn }}</td></tr>
            @endif
            <tr><td>Kelas saat kejadian</td><td>: {{ $record->penempatanKelas?->kelas?->nama_kelas ?? '—' }}{{ $record->penempatanKelas?->kelas?->jurusan ? ' · '.$record->penempatanKelas->kelas->jurusan : '' }}</td></tr>
            <tr><td>Tahun ajaran</td><td>: {{ $record->penempatanKelas?->tahunAjaran?->nama_tahun_ajaran ?? '—' }}</td></tr>
            <tr><td>Tanggal kejadian</td><td>: {{ $record->tanggal_pelanggaran?->locale('id')->translatedFormat('d F Y') ?? '—' }}</td></tr>
            <tr><td>Kategori pelanggaran</td><td>: {{ $record->pelanggaran?->kategori?->nama_kategori ?? '—' }}</td></tr>
            <tr><td>Jenis pelanggaran</td><td>: {{ $record->pelanggaran?->nama_pelanggaran ?? '—' }}</td></tr>
            <tr><td>Poin saat dicatat</td><td>: {{ number_format($record->poin_saat_dicatat, 0, ',', '.') }} poin</td></tr>
            <tr><td>Guru pencatat</td><td>: {{ $record->guru?->nama ?? '—' }}</td></tr>
        </tbody>
    </table>

    <p><strong>Keterangan kejadian:</strong><br>{{ $record->keterangan ?: 'Tidak ada keterangan yang dicatat.' }}</p>
    <p><strong>Tindakan/tindak lanjut:</strong><br>{{ $record->tindakan ?: 'Belum ada tindak lanjut yang dicatat.' }}</p>
    <p>Mohon kerja sama Bapak/Ibu untuk memberikan perhatian dan pembinaan kepada siswa. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>

    <table class="signatures">
        <tbody>
            <tr>
                <td>Orang Tua/Wali,<div class="name">(........................................)</div></td>
                <td>Guru pencatat,<div class="name">{{ $record->guru?->nama ?? '(........................................)' }}</div></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
