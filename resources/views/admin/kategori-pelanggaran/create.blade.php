@extends('layouts.admin')
@section('title', 'Tambah Kategori Pelanggaran')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Kategori Pelanggaran', 'url' => route('admin.violation-categories.index')], ['label' => 'Tambah']]" />@endsection
@section('page-heading')<x-ui.page-heading title="Tambah Kategori" description="Buat kategori baru untuk master pelanggaran." />@endsection
@section('content')
<x-ui.card>
    <form method="POST" action="{{ route('admin.violation-categories.store') }}">
        @csrf
        @if($errors->any())<x-ui.error-state title="Kategori belum tersimpan" description="Periksa kembali data yang dimasukkan." />@endif
        @include('admin.kategori-pelanggaran._form', ['submitLabel' => 'Simpan kategori'])
    </form>
</x-ui.card>
@endsection
