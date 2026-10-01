@extends('layouts.admin')
@section('content')
    <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6">
        <a class="text-sm text-gray-600 underline" href="{{ route('admin.academic-years.index') }}">← Tahun Ajaran</a>
        @if (session('success'))
            <x-ui.toast>{{ session('success') }}</x-ui.toast>
        @endif
        <div class="flex flex-wrap items-start justify-between gap-4 rounded-xl border border-gray-200 bg-white p-6">
            <div>
                <p class="text-sm text-gray-500">Periode akademik</p>
                <h1 class="mt-1 text-2xl font-semibold">{{ $academicYear->nama_tahun_ajaran }}</h1>
                <p class="mt-3 text-gray-600">{{ $academicYear->tanggal_mulai->format('d F Y') }} –
                    {{ $academicYear->tanggal_selesai->format('d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.badge>{{ $academicYear->status_aktif ? 'Aktif' : 'Nonaktif' }}</x-ui.badge><x-ui.button
                    variant="secondary" :href="route('admin.academic-years.edit', $academicYear)">Edit periode</x-ui.button></div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2"><x-ui.stat-card label="Riwayat penempatan siswa"
                :value="$academicYear->penempatan_siswa_count" /><x-ui.stat-card label="Riwayat penugasan wali kelas" :value="$academicYear->penugasan_wali_kelas_count" /></div>
        <p class="text-sm text-gray-600">Riwayat penempatan dan penugasan tetap tersimpan saat status atau periode
            diperbarui.</p>
    </div>
@endsection
