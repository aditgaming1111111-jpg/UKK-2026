@extends('layouts.admin')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Tahun Ajaran', 'url' => route('admin.academic-years.index')], ['label' => 'Tambah']]" />
@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')
    <x-ui.page-heading title="Tambah Tahun Ajaran" description="Tanggal periode tidak boleh bertumpang tindih dengan periode lain." />
@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    <div class="max-w-3xl">
        <x-ui.card>
            {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
            <form method="POST" action="{{ route('admin.academic-years.store') }}" class="space-y-5">
                @if ($errors->any())
                    <x-ui.error-state title="Tahun ajaran belum tersimpan" description="Periksa kembali isian yang ditandai." />
                @endif

                {{-- Memakai partial bersama agar struktur formulir tetap konsisten. --}}
                @include('admin.tahun-ajaran._form', ['submitLabel' => 'Simpan tahun ajaran'])
            </form>
        </x-ui.card>
    </div>
@endsection
