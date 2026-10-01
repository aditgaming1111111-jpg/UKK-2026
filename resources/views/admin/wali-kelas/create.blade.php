@extends('layouts.admin')
@section('title', 'Tugaskan Wali Kelas')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Wali Kelas', 'url' => route('admin.homeroom-teachers.index')], ['label' => 'Penugasan baru']]" />@endsection
@section('page-heading')<x-ui.page-heading title="Tugaskan Wali Kelas" description="Guru aktif dapat ditugaskan pada kelas dan tahun ajaran yang dipilih." />@endsection
@section('content')
<x-ui.card>
<form method="POST" action="{{ route('admin.homeroom-teachers.store') }}" class="space-y-5">
@csrf
@if($errors->any())
<x-ui.error-state title="Penugasan belum tersimpan" description="Periksa pilihan dan tanggal. Jadwal yang bertabrakan akan ditolak." />
@endif
<x-ui.select label="Guru" name="guru_id" required><option value="">Pilih guru aktif</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}" @selected(old('guru_id') == $teacher->id)>{{ $teacher->nama }} — {{ $teacher->nip }}</option>@endforeach</x-ui.select>
<x-ui.select label="Kelas" name="kelas_id" required><option value="">Pilih kelas</option>@foreach($classes as $class)<option value="{{ $class->id }}" @selected(old('kelas_id') == $class->id)>{{ $class->nama_kelas }} ({{ $class->tingkat }})</option>@endforeach</x-ui.select>
<x-ui.select label="Tahun ajaran" name="tahun_ajaran_id" required><option value="">Pilih tahun ajaran</option>@foreach($academicYears as $year)<option value="{{ $year->id }}" @selected(old('tahun_ajaran_id') == $year->id)>{{ $year->nama_tahun_ajaran }} · {{ $year->tanggal_mulai->format('d/m/Y') }}–{{ $year->tanggal_selesai->format('d/m/Y') }}</option>@endforeach</x-ui.select>
<x-ui.input label="Tanggal mulai tugas" name="tanggal_mulai" type="date" :value="old('tanggal_mulai')" required/><div class="flex justify-end gap-3"><x-ui.button variant="secondary" :href="route('admin.homeroom-teachers.index')">Batal</x-ui.button><x-ui.button type="submit">Simpan penugasan</x-ui.button></div></form></x-ui.card>@endsection
