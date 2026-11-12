@extends('layouts.admin')
{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Edit Jenis Pelanggaran')
{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[
    ['label' => 'Jenis Pelanggaran', 'url' => route('admin.violations.index')],
    ['label' => $violation->kode, 'url' => route('admin.violations.show', $violation)],
    ['label' => 'Edit'],
]" />@endsection
{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Edit Jenis Pelanggaran"
    description="Perubahan poin master tidak mengubah snapshot poin pada catatan sebelumnya." />@endsection
{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="POST" action="{{ route('admin.violations.update', $violation) }}">
            @method('PUT')
            @if ($errors->any())
                <x-ui.error-state title="Jenis belum diperbarui" description="Periksa kode, kategori, dan nilai poin." />
            @endif
            {{-- Memakai partial bersama agar struktur formulir tetap konsisten. --}}
            @include('admin.jenis-pelanggaran._form', ['submitLabel' => 'Simpan perubahan'])
        </form>
    </x-ui.card>
@endsection
