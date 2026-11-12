@extends('layouts.admin')
{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Edit Kelas')
{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[
    ['label' => 'Data Kelas', 'url' => route('admin.classes.index')],
    ['label' => $kelas->nama_kelas, 'url' => route('admin.classes.show', $kelas)],
    ['label' => 'Edit'],
]" />@endsection
{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Edit Kelas"
    description="Perubahan nama, tingkat, atau jurusan tidak mengubah riwayat penempatan." />@endsection
{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')<x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="POST" action="{{ route('admin.classes.update', $kelas) }}">
            @method('PUT')@include('admin.kelas._form', ['submitLabel' => 'Simpan perubahan'])</form>
</x-ui.card>@endsection
