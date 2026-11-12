@extends('layouts.admin')

{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Edit Guru')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[
    ['label' => 'Data Guru', 'url' => route('admin.teachers.index')],
    ['label' => $teacher->nama, 'url' => route('admin.teachers.show', $teacher)],
    ['label' => 'Edit Identitas'],
]" />@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Edit Identitas Guru"
    description="Perbarui data Guru dan email yang digunakan untuk login." />@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
            @method('PUT')@include('admin.guru._form', ['submitLabel' => 'Simpan perubahan'])</form>
    </x-ui.card>
@endsection
