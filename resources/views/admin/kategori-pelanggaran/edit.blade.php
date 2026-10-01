@extends('layouts.admin')
@section('title', 'Edit Kategori Pelanggaran')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Kategori Pelanggaran', 'url' => route('admin.violation-categories.index')], ['label' => $category->nama_kategori, 'url' => route('admin.violation-categories.show', $category)], ['label' => 'Edit']]" />@endsection
@section('page-heading')<x-ui.page-heading title="Edit Kategori" description="Perubahan nama kategori tidak menghapus jenis maupun catatan historis." />@endsection
@section('content')
<x-ui.card>
    <form method="POST" action="{{ route('admin.violation-categories.update', $category) }}">
        @csrf
        @method('PUT')
        @if($errors->any())<x-ui.error-state title="Kategori belum diperbarui" description="Periksa kembali data yang dimasukkan." />@endif
        @include('admin.kategori-pelanggaran._form', ['submitLabel' => 'Simpan perubahan'])
    </form>
</x-ui.card>
@endsection
