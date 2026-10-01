@extends('layouts.admin')

@section('title', 'Edit Guru')

@section('breadcrumbs')<x-ui.breadcrumbs :items="[
    ['label' => 'Data Guru', 'url' => route('admin.teachers.index')],
    ['label' => $teacher->nama, 'url' => route('admin.teachers.show', $teacher)],
    ['label' => 'Edit Identitas'],
]" />@endsection

@section('page-heading')<x-ui.page-heading title="Edit Identitas Guru"
    description="Perbarui data {{ $teacher->nama }}. Email login dikelola terpisah." />@endsection

@section('content')
    <x-ui.card>
        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
            @method('PUT')@include('admin.guru._form', ['submitLabel' => 'Simpan perubahan'])</form>
    </x-ui.card>
@endsection
