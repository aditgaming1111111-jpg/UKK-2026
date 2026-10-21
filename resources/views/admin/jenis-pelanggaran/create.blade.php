@extends('layouts.admin')
@section('title', 'Tambah Jenis Pelanggaran')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Jenis Pelanggaran', 'url' => route('admin.violations.index')], ['label' => 'Tambah']]" />@endsection
@section('page-heading')<x-ui.page-heading title="Tambah Jenis Pelanggaran"
    description="Jenis baru hanya dapat memakai kategori aktif." />@endsection
@section('content')
    <x-ui.card>
        @if ($categories->isEmpty())
            <x-ui.empty-state title="Belum ada kategori aktif"
                description="Aktifkan atau buat kategori pelanggaran sebelum menambahkan jenis." />
            <div class="mt-4 text-center"><x-ui.button variant="secondary" :href="route('admin.violation-categories.index')">Kelola kategori</x-ui.button>
            </div>
        @else
            <form method="POST" action="{{ route('admin.violations.store') }}">
                @if ($errors->any())
                    <x-ui.error-state title="Jenis belum tersimpan" description="Periksa kode, kategori, dan nilai poin." />
                @endif
                @include('admin.jenis-pelanggaran._form', ['submitLabel' => 'Simpan jenis'])
            </form>
        @endif
    </x-ui.card>
@endsection
