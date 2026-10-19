@extends('layouts.admin')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Tahun Ajaran']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Tahun Ajaran" description="Periode lama tetap tersimpan untuk menjaga riwayat penempatan dan wali kelas.">
        <x-ui.button :href="route('admin.academic-years.create')">Tambah tahun ajaran</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <x-ui.toast>{{ session('success') }}</x-ui.toast>
        @endif

        <x-ui.card>
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                <x-ui.input label="Cari nama" name="q" :value="$filters['q'] ?? ''" />
                <x-ui.select label="Status" name="status">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif</option>
                </x-ui.select>
                <div class="flex items-end gap-2">
                    <x-ui.button type="submit">Terapkan</x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.academic-years.index')">Reset</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card :padding="false">
            @if ($academicYears->isNotEmpty())
                <x-ui.table>
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
                        @foreach ($academicYears as $year)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">{{ $year->nama_tahun_ajaran }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $year->tanggal_mulai->format('d/m/Y') }} – {{ $year->tanggal_selesai->format('d/m/Y') }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $year->penempatan_siswa_count }} penempatan · {{ $year->penugasan_wali_kelas_count }} wali kelas</td>
                                <td class="px-5 py-4"><x-ui.badge :variant="$year->status_aktif ? 'dark' : 'outline'">{{ $year->status_aktif ? 'Aktif' : 'Nonaktif' }}</x-ui.badge></td>
                                <td class="whitespace-nowrap px-5 py-4 text-right"><a class="font-medium text-gray-700 underline decoration-gray-300 underline-offset-4 hover:decoration-gray-900" href="{{ route('admin.academic-years.show', $year) }}">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-ui.table>
                <div class="border-t border-gray-200 px-5 py-4"><x-ui.pagination :paginator="$academicYears" /></div>
            @else
                <div class="p-5"><x-ui.empty-state title="Belum ada data tahun ajaran" description="Tahun ajaran yang cocok dengan pencarian dan filter akan tampil di sini." /></div>
            @endif
        </x-ui.card>
    </div>
@endsection
