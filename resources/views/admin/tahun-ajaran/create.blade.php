@extends('layouts.admin')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Tahun Ajaran', 'url' => route('admin.academic-years.index')], ['label' => 'Tambah']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Tambah Tahun Ajaran" description="Tanggal periode tidak boleh bertumpang tindih dengan periode lain." />
@endsection

@section('content')
    <div class="max-w-3xl">
        <x-ui.card>
            <form method="POST" action="{{ route('admin.academic-years.store') }}" class="space-y-5">
                @if ($errors->any())
                    <x-ui.error-state title="Tahun ajaran belum tersimpan" description="Periksa kembali isian yang ditandai." />
                @endif

                @include('admin.tahun-ajaran._form', ['submitLabel' => 'Simpan tahun ajaran'])
            </form>
        </x-ui.card>
    </div>
@endsection
