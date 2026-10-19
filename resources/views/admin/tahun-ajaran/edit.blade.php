@extends('layouts.admin')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Tahun Ajaran', 'url' => route('admin.academic-years.index')], ['label' => $academicYear->nama_tahun_ajaran, 'url' => route('admin.academic-years.show', $academicYear)], ['label' => 'Edit']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Edit Tahun Ajaran" description="Perubahan periode divalidasi terhadap tahun ajaran lain." />
@endsection

@section('content')
    <div class="max-w-3xl">
        <x-ui.card>
            <form method="POST" action="{{ route('admin.academic-years.update', $academicYear) }}" class="space-y-5">
                @method('PUT')
                @if ($errors->any())
                    <x-ui.error-state title="Tahun ajaran belum diperbarui" description="Periksa kembali isian yang ditandai." />
                @endif

                @include('admin.tahun-ajaran._form', ['submitLabel' => 'Simpan perubahan'])
            </form>
        </x-ui.card>
    </div>
@endsection
