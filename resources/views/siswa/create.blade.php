@extends('layouts.admin')

{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Tambah Siswa')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Data Siswa', 'url' => route('admin.students.index')], ['label' => 'Tambah Siswa']]" />
@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')
    <x-ui.page-heading title="Tambah Siswa" description="Buat identitas siswa. NIS dan NISN menjadi identitas tetap siswa." />
@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="POST" action="{{ route('admin.students.store') }}">
            {{-- Memakai partial bersama agar struktur formulir tetap konsisten. --}}
            @include('siswa._form', ['submitLabel' => 'Simpan siswa'])
        </form>
    </x-ui.card>
@endsection
