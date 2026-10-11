@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.guru')

@section('title', 'Koreksi Catatan Pelanggaran')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Riwayat Pelanggaran', 'url' => route($routePrefix.'.index')],
        ['label' => 'Detail Catatan', 'url' => route($routePrefix.'.show', $record)],
        ['label' => 'Koreksi'],
    ]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Koreksi Catatan" description="Siswa, guru, kelas, dan tahun ajaran tetap melekat. Setiap koreksi wajib memiliki alasan audit." />
@endsection

@section('content')
    @if ($errors->has('catatan'))
        <div role="alert" class="mb-4 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800">{{ $errors->first('catatan') }}</div>
    @endif
    <form method="POST" action="{{ route($routePrefix.'.update', $record) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <x-ui.card>
            <h2 class="text-base font-semibold text-gray-950">Identitas catatan</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div><dt class="text-xs uppercase tracking-wide text-gray-500">Siswa</dt><dd class="mt-1 text-sm font-medium">{{ $record->penempatanKelas->siswa->nama }} · {{ $record->penempatanKelas->siswa->nisn }}</dd></div>
                <div><dt class="text-xs uppercase tracking-wide text-gray-500">Kelas kejadian</dt><dd class="mt-1 text-sm">{{ $record->penempatanKelas->kelas->nama_kelas }}</dd></div>
                <div><dt class="text-xs uppercase tracking-wide text-gray-500">Tahun ajaran</dt><dd class="mt-1 text-sm">{{ $record->penempatanKelas->tahunAjaran->nama_tahun_ajaran }}</dd></div>
                <div><dt class="text-xs uppercase tracking-wide text-gray-500">Poin snapshot sekarang</dt><dd class="mt-1 text-sm font-semibold">{{ $record->poin_saat_dicatat }} poin</dd></div>
            </dl>
        </x-ui.card>

        <x-ui.card>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Jenis pelanggaran</span>
                    <select name="pelanggaran_id" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                        @foreach ($violations as $violation)
                            <option value="{{ $violation->id }}" @selected(old('pelanggaran_id', $record->pelanggaran_id) == $violation->id)>{{ $violation->kode }} · {{ $violation->nama_pelanggaran }} · {{ $violation->poin }} poin{{ $violation->status_aktif ? '' : ' (nonaktif, jenis lama)' }}</option>
                        @endforeach
                    </select>
                    <x-ui.form-error name="pelanggaran_id" />
                </label>
                <div><x-ui.input label="Tanggal kejadian" name="tanggal_pelanggaran" type="date" :value="$record->tanggal_pelanggaran->toDateString()" required /><x-ui.form-error name="tanggal_pelanggaran" /></div>
                <x-ui.textarea label="Keterangan kejadian" name="keterangan" rows="4">{{ $record->keterangan }}</x-ui.textarea>
                <x-ui.textarea label="Tindakan" name="tindakan" rows="4">{{ $record->tindakan }}</x-ui.textarea>
                <div class="md:col-span-2"><x-ui.textarea label="Alasan koreksi" name="alasan" rows="3" required placeholder="Jelaskan mengapa catatan perlu dikoreksi." /></div>
            </div>
            <p class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-3 text-xs text-gray-600">Jika jenis pelanggaran diganti, poin snapshot akan mengikuti nilai master jenis baru saat koreksi. Jika hanya kolom lain yang berubah, snapshot lama tetap dipertahankan.</p>
        </x-ui.card>

        <div class="flex justify-end gap-2"><x-ui.button variant="secondary" :href="route($routePrefix.'.show', $record)">Batal</x-ui.button><x-ui.button type="submit">Simpan koreksi</x-ui.button></div>
    </form>
@endsection
