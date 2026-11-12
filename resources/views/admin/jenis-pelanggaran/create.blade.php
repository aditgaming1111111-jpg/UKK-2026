@extends('layouts.admin')
{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Tambah Jenis Pelanggaran')
{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Jenis Pelanggaran', 'url' => route('admin.violations.index')], ['label' => 'Tambah']]" />@endsection
{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Tambah Jenis Pelanggaran"
    description="Jenis baru hanya dapat memakai kategori aktif." />@endsection
{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        @if ($categories->isEmpty())
            <x-ui.empty-state title="Belum ada kategori aktif"
                description="Aktifkan atau buat kategori pelanggaran sebelum menambahkan jenis." />
            <div class="mt-4 text-center"><x-ui.button variant="secondary" :href="route('admin.violation-categories.index')">Kelola kategori</x-ui.button>
            </div>
        @else
            {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
            <form method="POST" action="{{ route('admin.violations.store') }}">
                @if ($errors->any())
                    <x-ui.error-state title="Jenis belum tersimpan" description="Periksa kode, kategori, dan nilai poin." />
                @endif
                {{-- Memakai partial bersama agar struktur formulir tetap konsisten. --}}
                @include('admin.jenis-pelanggaran._form', ['submitLabel' => 'Simpan jenis'])
            </form>
        @endif
    </x-ui.card>
@endsection
