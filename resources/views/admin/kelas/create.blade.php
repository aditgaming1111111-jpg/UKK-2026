@extends('layouts.admin')
{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Tambah Kelas')
{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Data Kelas', 'url' => route('admin.classes.index')], ['label' => 'Tambah Kelas']]" />@endsection
{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Tambah Kelas"
    description="Isi data kelas sesuai struktur database." />@endsection
{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')<x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="POST" action="{{ route('admin.classes.store') }}">@include('admin.kelas._form', ['submitLabel' => 'Simpan kelas'])</form>
</x-ui.card>@endsection
