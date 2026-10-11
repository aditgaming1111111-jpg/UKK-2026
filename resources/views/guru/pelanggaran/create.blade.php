@extends('layouts.guru')

@section('title', 'Catat Pelanggaran')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Dashboard', 'url' => route('guru.dashboard')],
        ['label' => 'Catat Pelanggaran'],
    ]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Catat Pelanggaran" description="Poin, guru pencatat, kelas, dan tahun ajaran dikunci berdasarkan data server.">
        <x-ui.button variant="secondary" :href="route('guru.students.index')">Cari siswa</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    @if ($errors->has('akun'))
        <div role="alert" class="mb-4 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800">{{ $errors->first('akun') }}</div>
    @endif

    <form method="POST" action="{{ route('guru.violations.store') }}" class="space-y-5" data-violation-form
        data-search-url="{{ route('guru.violations.student-search') }}">
        @csrf
        <x-ui.card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-gray-950">Siswa dan waktu kejadian</h2>
                <p class="mt-1 text-sm text-gray-500">Cari berdasarkan NISN atau nama. Penempatan harus berlaku pada tanggal kejadian.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="relative md:col-span-2">
                    <x-ui.input label="Cari siswa (NISN atau nama)" name="pencarian_siswa" :value="old('pencarian_siswa')"
                        placeholder="Contoh: 0081234567 atau nama siswa" autocomplete="off" data-student-query />
                    <div class="absolute inset-x-0 top-full z-10 mt-1 hidden max-h-72 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                        data-student-results role="listbox" aria-label="Hasil pencarian siswa"></div>
                </div>
                <div>
                    <x-ui.input label="Tanggal kejadian" name="tanggal_pelanggaran" type="date"
                        :value="old('tanggal_pelanggaran', today()->toDateString())" required data-incident-date />
                </div>
                <div class="flex items-end text-xs text-gray-500">Tanggal pencatatan otomatis mengikuti waktu penyimpanan.</div>
            </div>

            <input type="hidden" name="kelas_siswa_id" value="" data-placement-id>
            <x-ui.form-error name="kelas_siswa_id" />
            <div class="mt-4 grid gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4 sm:grid-cols-2 lg:grid-cols-4" data-selected-student aria-live="polite">
                <p class="text-sm text-gray-500" data-student-placeholder>Pilih hasil pencarian untuk melihat identitas dan penempatan.</p>
                <div class="hidden" data-student-details>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Nama</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900" data-student-name>—</p>
                </div>
                <div class="hidden" data-student-details>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">NISN</p>
                    <p class="mt-1 text-sm text-gray-800" data-student-nisn>—</p>
                </div>
                <div class="hidden" data-student-details>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Kelas saat kejadian</p>
                    <p class="mt-1 text-sm text-gray-800" data-student-class>—</p>
                </div>
                <div class="hidden" data-student-details>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Tahun ajaran</p>
                    <p class="mt-1 text-sm text-gray-800" data-student-year>—</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-gray-950">Rincian pelanggaran</h2>
                <p class="mt-1 text-sm text-gray-500">Poin ditetapkan dari jenis pelanggaran aktif di data master saat disimpan.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-1.5">
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="kategori_id" data-category-select
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('kategori_id') == $category->id)>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label for="pelanggaran_id" class="block text-sm font-medium text-gray-700">Jenis pelanggaran <span class="text-gray-500">*</span></label>
                    <select id="pelanggaran_id" name="pelanggaran_id" required data-violation-select
                        class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 {{ $errors->has('pelanggaran_id') ? 'border-gray-500' : 'border-gray-300' }}">
                        <option value="" data-violation-placeholder>Pilih kategori terlebih dahulu</option>
                        @foreach ($violations as $violation)
                            <option value="{{ $violation->id }}" data-category-id="{{ $violation->kategori_id }}" data-points="{{ $violation->poin }}"
                                @selected(old('pelanggaran_id') == $violation->id)>{{ $violation->kode }} — {{ $violation->nama_pelanggaran }}</option>
                        @endforeach
                    </select>
                    <x-ui.form-error name="pelanggaran_id" />
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 md:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Poin dari data master</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900" data-points-preview aria-live="polite">Pilih jenis pelanggaran</p>
                </div>
                <x-ui.textarea label="Keterangan kejadian" name="keterangan" rows="4" placeholder="Jelaskan kejadian secara ringkas dan faktual." />
                <x-ui.textarea label="Tindakan yang dilakukan" name="tindakan" rows="4" placeholder="Tuliskan tindak lanjut atau pembinaan yang telah dilakukan." />
            </div>
        </x-ui.card>

        <div class="flex flex-col-reverse justify-end gap-2 sm:flex-row">
            <x-ui.button variant="secondary" :href="route('guru.dashboard')">Batal</x-ui.button>
            <x-ui.button type="submit">Simpan catatan</x-ui.button>
        </div>
    </form>
@endsection
