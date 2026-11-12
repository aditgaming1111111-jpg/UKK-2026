@extends('layouts.admin')

{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Edit Siswa')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Data Siswa', 'url' => route('admin.students.index')],
        ['label' => $student->nama, 'url' => route('admin.students.show', $student)],
        ['label' => 'Edit'],
    ]" />
@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')
    <x-ui.page-heading title="Edit Data Siswa" description="Perbarui identitas {{ $student->nama }}." />
@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="POST" action="{{ route('admin.students.update', $student) }}">
            @method('PUT')
            {{-- Memakai partial bersama agar struktur formulir tetap konsisten. --}}
            @include('siswa._form', ['submitLabel' => 'Simpan perubahan'])
        </form>
    </x-ui.card>
@endsection
