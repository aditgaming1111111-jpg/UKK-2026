@extends('layouts.admin')
@section('title', 'Edit Jenis Pelanggaran')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Jenis Pelanggaran', 'url' => route('admin.violations.index')], ['label' => $violation->kode, 'url' => route('admin.violations.show', $violation)], ['label' => 'Edit']]" />@endsection
@section('page-heading')<x-ui.page-heading title="Edit Jenis Pelanggaran" description="Perubahan poin master tidak mengubah snapshot poin pada catatan sebelumnya." />@endsection
@section('content')
<x-ui.card>
    <form method="POST" action="{{ route('admin.violations.update', $violation) }}">
        @method('PUT')
        @if($errors->any())<x-ui.error-state title="Jenis belum diperbarui" description="Periksa kode, kategori, dan nilai poin." />@endif
        @include('admin.jenis-pelanggaran._form', ['submitLabel' => 'Simpan perubahan'])
    </form>
</x-ui.card>
@endsection
