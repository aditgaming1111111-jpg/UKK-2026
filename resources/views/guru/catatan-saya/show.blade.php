<div>
    <!-- You must be the change you wish to see in the world. - Mahatma Gandhi -->
</div>
@extends('layouts.guru')

@section('title', 'Detail Catatan Saya')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Catatan Saya', 'url' => route('guru.my-notes.index')],
        ['label' => 'Detail catatan'],
    ]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Detail Catatan Saya" description="Informasi kejadian dan jejak audit catatan pelanggaran.">
        <x-ui.button variant="secondary" :href="route('guru.my-notes.index')">Kembali ke Catatan Saya</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    <section class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <x-ui.card>
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-950">{{ $record->pelanggaran->nama_pelanggaran }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $record->pelanggaran->kode }} · {{ $record->pelanggaran->kategori->nama_kategori }}</p>
                </div>
                <x-ui.badge :variant="$record->status === 'aktif' ? 'dark' : 'outline'">{{ $record->status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}</x-ui.badge>
            </div>

            <dl class="mt-6 grid gap-x-6 gap-y-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Siswa</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $record->penempatanKelas->siswa->nama }}</dd>
                    <dd class="text-sm text-gray-600">NISN {{ $record->penempatanKelas->siswa->nisn }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Kelas saat kejadian</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $record->penempatanKelas->kelas->nama_kelas }} · {{ $record->penempatanKelas->kelas->tingkat }}{{ $record->penempatanKelas->kelas->jurusan ? ' · '.$record->penempatanKelas->kelas->jurusan : '' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tahun ajaran</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $record->penempatanKelas->tahunAjaran->nama_tahun_ajaran }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Guru pencatat</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $record->guru->nama }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tanggal pelanggaran</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $record->tanggal_pelanggaran->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tanggal pencatatan</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $record->tanggal_dicatat->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Poin saat dicatat</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($record->poin_saat_dicatat, 0, ',', '.') }} poin</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Keterangan kejadian</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $record->keterangan ?: 'Tidak ada keterangan.' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tindakan yang dilakukan</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $record->tindakan ?: 'Belum ada tindakan yang dicatat.' }}</dd>
                </div>
                @if ($record->status === 'dibatalkan' && $cancellationLog?->alasan)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Alasan pembatalan</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $cancellationLog->alasan }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        <x-ui.card>
            <h2 class="text-base font-semibold text-gray-950">Riwayat audit</h2>
            <p class="mt-1 text-sm text-gray-500">Perubahan pada catatan ini dicatat beserta pengguna, waktu, dan alasan.</p>
            <p class="mt-4 text-2xl font-semibold text-gray-950">{{ $record->logPerubahan->count() }}</p>
            <p class="text-xs text-gray-500">entri audit</p>
        </x-ui.card>
    </section>

    <x-ui.card>
        <h2 class="text-base font-semibold text-gray-950">Jejak perubahan</h2>
        @if ($record->logPerubahan->isNotEmpty())
            <div class="mt-5 space-y-4">
                @foreach ($record->logPerubahan as $log)
                    <article class="rounded-lg border border-gray-200 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ ['tambah' => 'Pencatatan', 'edit' => 'Koreksi', 'batal' => 'Pembatalan'][$log->aksi] ?? ucfirst($log->aksi) }}</h3>
                                <p class="mt-1 text-xs text-gray-500">{{ $log->pengguna->nama }} · {{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-700"><span class="font-medium">Alasan:</span> {{ $log->alasan ?: 'Pencatatan awal' }}</p>
                        <div class="mt-3 grid gap-3 md:grid-cols-2">
                            <details class="rounded-md bg-gray-50 p-3">
                                <summary class="cursor-pointer text-xs font-medium text-gray-700">Data sebelum</summary>
                                <pre class="mt-2 overflow-x-auto whitespace-pre-wrap break-words text-[11px] text-gray-600">{{ $log->data_sebelum ?: 'Tidak ada (pencatatan awal).' }}</pre>
                            </details>
                            <details class="rounded-md bg-gray-50 p-3">
                                <summary class="cursor-pointer text-xs font-medium text-gray-700">Data sesudah</summary>
                                <pre class="mt-2 overflow-x-auto whitespace-pre-wrap break-words text-[11px] text-gray-600">{{ $log->data_sesudah ?: 'Tidak tersedia.' }}</pre>
                            </details>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <x-ui.empty-state class="mt-5" title="Belum ada perubahan" description="Log audit akan muncul setelah catatan dikoreksi atau dibatalkan." />
        @endif
    </x-ui.card>
@endsection
