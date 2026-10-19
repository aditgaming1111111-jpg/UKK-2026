@extends('layouts.admin')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Tahun Ajaran', 'url' => route('admin.academic-years.index')], ['label' => $academicYear->nama_tahun_ajaran]]" />
@endsection

@section('page-heading')
    <x-ui.page-heading :title="$academicYear->nama_tahun_ajaran" description="Periode akademik dan riwayat yang terkait.">
        <x-ui.button variant="secondary" :href="route('admin.academic-years.edit', $academicYear)">Edit periode</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <x-ui.toast>{{ session('success') }}</x-ui.toast>
        @endif

        <x-ui.card>
            <dl class="grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-2"><x-ui.badge :variant="$academicYear->status_aktif ? 'dark' : 'outline'">{{ $academicYear->status_aktif ? 'Aktif' : 'Nonaktif' }}</x-ui.badge></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Periode</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $academicYear->tanggal_mulai->format('d F Y') }} – {{ $academicYear->tanggal_selesai->format('d F Y') }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <section class="grid gap-4 sm:grid-cols-2">
            <x-ui.stat-card label="Riwayat penempatan siswa" :value="$academicYear->penempatan_siswa_count" />
            <x-ui.stat-card label="Riwayat penugasan wali kelas" :value="$academicYear->penugasan_wali_kelas_count" />
        </section>

        <p class="text-sm text-gray-600">Riwayat penempatan dan penugasan tetap tersimpan saat status atau periode diperbarui.</p>
    </div>
@endsection
