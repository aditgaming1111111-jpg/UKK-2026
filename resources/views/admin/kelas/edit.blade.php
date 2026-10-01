@extends('layouts.admin')
@section('title', 'Edit Kelas')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[
    ['label' => 'Data Kelas', 'url' => route('admin.classes.index')],
    ['label' => $kelas->nama_kelas, 'url' => route('admin.classes.show', $kelas)],
    ['label' => 'Edit'],
]" />@endsection
@section('page-heading')<x-ui.page-heading title="Edit Kelas"
    description="Perubahan nama, tingkat, atau jurusan tidak mengubah riwayat penempatan." />@endsection
@section('content')<x-ui.card>
        <form method="POST" action="{{ route('admin.classes.update', $kelas) }}">
            @method('PUT')@include('admin.kelas._form', ['submitLabel' => 'Simpan perubahan'])</form>
</x-ui.card>@endsection
