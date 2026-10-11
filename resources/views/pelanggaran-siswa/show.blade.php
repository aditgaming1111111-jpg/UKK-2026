@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.guru')

@section('title', 'Detail Catatan Pelanggaran')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Riwayat Pelanggaran', 'url' => route($routePrefix.'.index')],
        ['label' => 'Detail Catatan'],
    ]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Detail Catatan Pelanggaran" description="Catatan terhubung dengan kelas dan tahun ajaran saat kejadian.">
        <div class="flex flex-wrap gap-2">
            @if ($canManage)
                <x-ui.button variant="secondary" :href="route($routePrefix.'.edit', $record)">Koreksi catatan</x-ui.button>
                <x-ui.button type="button" data-modal-open="cancel-record">Batalkan catatan</x-ui.button>
            @endif
            <x-ui.button variant="secondary" :href="route($routePrefix.'.index')">Kembali ke riwayat</x-ui.button>
        </div>
    </x-ui.page-heading>
@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">{{ session('success') }}</div>
    @endif
    @if ($errors->has('catatan'))
        <div role="alert" class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800">{{ $errors->first('catatan') }}</div>
    @endif
    @if ($errors->has('alasan'))
        <div role="alert" class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800">{{ $errors->first('alasan') }}</div>
    @endif

    <section class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <x-ui.card>
            <div class="flex items-start justify-between gap-3"><div><h2 class="text-lg font-semibold text-gray-950">{{ $record->pelanggaran->nama_pelanggaran }}</h2><p class="mt-1 text-sm text-gray-500">{{ $record->pelanggaran->kode }} · {{ $record->pelanggaran->kategori->nama_kategori }}</p></div><x-ui.badge :variant="$record->status === 'aktif' ? 'dark' : 'outline'">{{ $record->status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}</x-ui.badge></div>
            <dl class="mt-6 grid gap-x-6 gap-y-5 sm:grid-cols-2">
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Siswa</dt><dd class="mt-1 font-medium text-gray-900">{{ $record->penempatanKelas->siswa->nama }}</dd><dd class="text-sm text-gray-600">NISN {{ $record->penempatanKelas->siswa->nisn }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tanggal kejadian</dt><dd class="mt-1 text-sm text-gray-800">{{ $record->tanggal_pelanggaran->format('d/m/Y') }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Kelas saat kejadian</dt><dd class="mt-1 text-sm text-gray-800">{{ $record->penempatanKelas->kelas->nama_kelas }} · {{ $record->penempatanKelas->kelas->tingkat }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tahun ajaran</dt><dd class="mt-1 text-sm text-gray-800">{{ $record->penempatanKelas->tahunAjaran->nama_tahun_ajaran }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Guru pencatat</dt><dd class="mt-1 text-sm text-gray-800">{{ $record->guru->nama }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Waktu pencatatan pertama</dt><dd class="mt-1 text-sm text-gray-800">{{ $record->tanggal_dicatat->format('d/m/Y H:i') }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Poin snapshot</dt><dd class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($record->poin_saat_dicatat, 0, ',', '.') }} poin</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Poin master saat ini</dt><dd class="mt-1 text-sm text-gray-800">{{ $record->pelanggaran->poin }} poin{{ $record->pelanggaran->status_aktif ? '' : ' · jenis nonaktif' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Keterangan kejadian</dt><dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $record->keterangan ?: 'Tidak ada keterangan.' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tindakan</dt><dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $record->tindakan ?: 'Belum ada tindakan yang dicatat.' }}</dd></div>
            </dl>
        </x-ui.card>

        <x-ui.card>
            <h2 class="text-base font-semibold text-gray-950">Audit perubahan</h2>
            <p class="mt-1 text-sm text-gray-500">Log hanya dapat dilihat dan tidak menyediakan aksi edit atau hapus.</p>
            <p class="mt-4 text-2xl font-semibold text-gray-950">{{ $record->logPerubahan->count() }}</p>
            <p class="text-xs text-gray-500">entri perubahan</p>
        </x-ui.card>
    </section>

    <x-ui.card>
        <h2 class="text-base font-semibold text-gray-950">Log audit</h2>
        <p class="mt-1 text-sm text-gray-500">Setiap entri menyimpan pengguna, aksi, alasan, data sebelum dan sesudah, serta waktu.</p>
        @if ($record->logPerubahan->isNotEmpty())
            <div class="mt-5 space-y-4">
                @foreach ($record->logPerubahan as $log)
                    <article class="rounded-lg border border-gray-200 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div><h3 class="text-sm font-semibold text-gray-900">{{ ['tambah' => 'Pencatatan', 'edit' => 'Koreksi', 'batal' => 'Pembatalan'][$log->aksi] ?? ucfirst($log->aksi) }}</h3><p class="mt-1 text-xs text-gray-500">{{ $log->pengguna->nama }} · {{ $log->created_at->format('d/m/Y H:i:s') }}</p></div>
                        </div>
                        <p class="mt-3 text-sm text-gray-700"><span class="font-medium">Alasan:</span> {{ $log->alasan ?: 'Pencatatan awal' }}</p>
                        <div class="mt-3 grid gap-3 md:grid-cols-2">
                            <details class="rounded-md bg-gray-50 p-3"><summary class="cursor-pointer text-xs font-medium text-gray-700">Data sebelum</summary><pre class="mt-2 overflow-x-auto whitespace-pre-wrap break-words text-[11px] text-gray-600">{{ $log->data_sebelum ?: 'Tidak ada (pencatatan awal).' }}</pre></details>
                            <details class="rounded-md bg-gray-50 p-3"><summary class="cursor-pointer text-xs font-medium text-gray-700">Data sesudah</summary><pre class="mt-2 overflow-x-auto whitespace-pre-wrap break-words text-[11px] text-gray-600">{{ $log->data_sesudah ?: 'Tidak tersedia.' }}</pre></details>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <x-ui.empty-state class="mt-5" title="Log belum tersedia" description="Perubahan pada catatan akan tercatat di sini." />
        @endif
    </x-ui.card>

    @if ($canManage)
        <x-ui.modal id="cancel-record" title="Batalkan catatan pelanggaran?" description="Catatan tetap tersimpan untuk riwayat, tetapi statusnya tidak lagi dihitung sebagai poin aktif.">
            <form method="POST" action="{{ route($routePrefix.'.cancel', $record) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <x-ui.textarea label="Alasan pembatalan" name="alasan" rows="3" required placeholder="Jelaskan alasan catatan dibatalkan." />
                <div class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Konfirmasi pembatalan</x-ui.button></div>
            </form>
        </x-ui.modal>
    @endif
@endsection
