@extends('layouts.admin')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">Pengaturan akademik</p>
                <h1 class="mt-1 text-2xl font-semibold text-gray-950">Tahun Ajaran</h1>
                <p class="mt-2 text-sm text-gray-600">Periode lama tetap tersimpan untuk menjaga riwayat penempatan dan wali
                    kelas.</p>
            </div><x-ui.button :href="route('admin.academic-years.create')">Tambah tahun ajaran</x-ui.button>
        </div>
        @if (session('success'))
            <x-ui.toast>{{ session('success') }}</x-ui.toast>
        @endif
        <form method="GET" class="flex flex-wrap items-end gap-3 rounded-xl border border-gray-200 bg-white p-4"><x-ui.input
                label="Cari nama" name="q" :value="$filters['q'] ?? ''" /><x-ui.select label="Status" name="status">
                <option value="">Semua status</option>
                <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif</option>
            </x-ui.select><x-ui.button type="submit">Terapkan</x-ui.button><x-ui.button variant="secondary"
                :href="route('admin.academic-years.index')">Reset</x-ui.button></form>
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Tahun ajaran</th>
                            <th class="px-5 py-3">Periode</th>
                            <th class="px-5 py-3">Riwayat</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($academicYears as $year)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">{{ $year->nama_tahun_ajaran }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $year->tanggal_mulai->format('d/m/Y') }} –
                                    {{ $year->tanggal_selesai->format('d/m/Y') }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $year->penempatan_siswa_count }} penempatan ·
                                    {{ $year->penugasan_wali_kelas_count }} wali kelas</td>
                                <td class="px-5 py-4">
                                    <x-ui.badge>{{ $year->status_aktif ? 'Aktif' : 'Nonaktif' }}</x-ui.badge></td>
                                <td class="px-5 py-4 text-right"><a class="font-medium text-gray-700 underline"
                                        href="{{ route('admin.academic-years.show', $year) }}">Detail</a></td>
                        </tr>@empty<tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-500">Belum ada data tahun ajaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-5 py-4">{{ $academicYears->links() }}</div>
        </div>
    </div>
@endsection
