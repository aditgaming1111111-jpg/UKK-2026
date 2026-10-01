@extends('layouts.admin')

@section('title', 'Edit Siswa')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Data Siswa', 'url' => route('admin.students.index')],
        ['label' => $student->nama, 'url' => route('admin.students.show', $student)],
        ['label' => 'Edit'],
    ]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Edit Data Siswa" description="Perbarui identitas {{ $student->nama }}." />
@endsection

@section('content')
    <x-ui.card>
        <form method="POST" action="{{ route('admin.students.update', $student) }}">
            @method('PUT')
            @include('siswa._form', ['submitLabel' => 'Simpan perubahan'])
        </form>
    </x-ui.card>
@endsection
