@extends('layouts.admin')
@section('title', 'Tambah Kelas')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Data Kelas', 'url' => route('admin.classes.index')], ['label' => 'Tambah Kelas']]" />@endsection
@section('page-heading')<x-ui.page-heading title="Tambah Kelas"
    description="Isi data kelas sesuai struktur database." />@endsection
@section('content')<x-ui.card>
        <form method="POST" action="{{ route('admin.classes.store') }}">@include('admin.kelas._form', ['submitLabel' => 'Simpan kelas'])</form>
</x-ui.card>@endsection
