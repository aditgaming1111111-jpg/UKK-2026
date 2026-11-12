@extends('layouts.admin')
{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Tambah Kategori Pelanggaran')
{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Kategori Pelanggaran', 'url' => route('admin.violation-categories.index')], ['label' => 'Tambah']]" />@endsection
{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Tambah Kategori" description="Buat kategori baru untuk master pelanggaran." />@endsection
{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
{{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
<x-ui.card>
    {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
    <form method="POST" action="{{ route('admin.violation-categories.store') }}">
        @csrf
        @if($errors->any())<x-ui.error-state title="Kategori belum tersimpan" description="Periksa kembali data yang dimasukkan." />@endif
        {{-- Memakai partial bersama agar struktur formulir tetap konsisten. --}}
        @include('admin.kategori-pelanggaran._form', ['submitLabel' => 'Simpan kategori'])
    </form>
</x-ui.card>
@endsection
