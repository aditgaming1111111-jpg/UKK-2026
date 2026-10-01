@extends('layouts.admin')

@section('title', 'Tambah Siswa')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Data Siswa', 'url' => route('admin.students.index')], ['label' => 'Tambah Siswa']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Tambah Siswa" description="Buat identitas siswa. NISN akan menjadi identitas tetap siswa." />
@endsection

@section('content')
    <x-ui.card>
        <form method="POST" action="{{ route('admin.students.store') }}">
            @include('siswa._form', ['submitLabel' => 'Simpan siswa'])
        </form>
    </x-ui.card>
@endsection
