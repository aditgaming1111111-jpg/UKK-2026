@extends('layouts.admin')
@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6"><a class="text-sm text-gray-600 underline"
            href="{{ route('admin.academic-years.show', $academicYear) }}">← Detail tahun ajaran</a>
        <h1 class="mt-4 text-2xl font-semibold">Edit Tahun Ajaran</h1>
        <p class="mt-2 text-sm text-gray-600">Perubahan periode divalidasi terhadap tahun ajaran lain.</p>
        <form method="POST" action="{{ route('admin.academic-years.update', $academicYear) }}"
            class="mt-6 rounded-xl border border-gray-200 bg-white p-6">@method('PUT')@if ($errors->any())
                <div class="mb-5 rounded-lg bg-gray-100 p-4 text-sm text-gray-700">Periksa kembali isian yang ditandai.</div>
            @endif
            @include('admin.tahun-ajaran._form', ['submitLabel' => 'Simpan perubahan'])</form>
    </div>
@endsection
