@extends('layouts.admin')
{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Edit Kategori Pelanggaran')
{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Kategori Pelanggaran', 'url' => route('admin.violation-categories.index')], ['label' => $category->nama_kategori, 'url' => route('admin.violation-categories.show', $category)], ['label' => 'Edit']]" />@endsection
{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Edit Kategori" description="Perubahan nama kategori tidak menghapus jenis maupun catatan historis." />@endsection
{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
{{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
<x-ui.card>
    {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
    <form method="POST" action="{{ route('admin.violation-categories.update', $category) }}">
        @csrf
        @method('PUT')
        @if($errors->any())<x-ui.error-state title="Kategori belum diperbarui" description="Periksa kembali data yang dimasukkan." />@endif
        {{-- Memakai partial bersama agar struktur formulir tetap konsisten. --}}
        @include('admin.kategori-pelanggaran._form', ['submitLabel' => 'Simpan perubahan'])
    </form>
</x-ui.card>
@endsection
