<div>
    <!-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci -->
</div>
@extends('layouts.guru')

@section('title', 'Catatan Saya')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Catatan Saya']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Catatan Saya" description="Catatan pelanggaran yang Anda buat, termasuk catatan yang telah dibatalkan.">
        <x-ui.button :href="route('guru.violations.create')">Catat pelanggaran</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    <x-ui.card>
        <form method="GET" action="{{ route('guru.my-notes.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.search placeholder="Cari nama siswa atau NISN" :value="$filters['q'] ?? null" class="lg:col-span-2" />
            <label class="space-y-1.5">
                <span class="block text-sm font-medium text-gray-700">Tahun ajaran</span>
                <select name="tahun_ajaran_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua tahun ajaran</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected(($filters['tahun_ajaran_id'] ?? '') == $year->id)>{{ $year->nama_tahun_ajaran }}</option>
                    @endforeach
                </select>
                <x-ui.form-error name="tahun_ajaran_id" />
            </label>
            <label class="space-y-1.5">
                <span class="block text-sm font-medium text-gray-700">Kelas saat kejadian</span>
                <select name="kelas_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(($filters['kelas_id'] ?? '') == $class->id)>{{ $class->nama_kelas }} · {{ $class->tingkat }}</option>
                    @endforeach
                </select>
                <x-ui.form-error name="kelas_id" />
            </label>
            <label class="space-y-1.5">
                <span class="block text-sm font-medium text-gray-700">Tanggal mulai</span>
                <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                <x-ui.form-error name="tanggal_mulai" />
            </label>
            <label class="space-y-1.5">
                <span class="block text-sm font-medium text-gray-700">Tanggal akhir</span>
                <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                <x-ui.form-error name="tanggal_selesai" />
            </label>
            <label class="space-y-1.5">
                <span class="block text-sm font-medium text-gray-700">Status catatan</span>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                    <option value="dibatalkan" @selected(($filters['status'] ?? '') === 'dibatalkan')>Dibatalkan</option>
                </select>
                <x-ui.form-error name="status" />
            </label>
            <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-4">
                <x-ui.button type="submit">Terapkan filter</x-ui.button>
                <x-ui.button variant="secondary" :href="route('guru.my-notes.index')">Reset</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card :padding="false">
        @if ($records->isNotEmpty())
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Tanggal kejadian</th>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Kelas / Tahun</th>
                        <th class="px-4 py-3">Jenis pelanggaran</th>
                        <th class="px-4 py-3">Poin snapshot</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ $record->tanggal_pelanggaran->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="block font-medium text-gray-900">{{ $record->penempatanKelas->siswa->nama }}</span>
                                <span class="text-xs text-gray-500">NISN {{ $record->penempatanKelas->siswa->nisn }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $record->penempatanKelas->kelas->nama_kelas }}
                                <span class="mt-1 block text-xs text-gray-500">{{ $record->penempatanKelas->tahunAjaran->nama_tahun_ajaran }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $record->pelanggaran->nama_pelanggaran }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-800">{{ number_format($record->poin_saat_dicatat, 0, ',', '.') }} poin</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$record->status === 'aktif' ? 'dark' : 'outline'">{{ $record->status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}</x-ui.badge>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <a class="text-sm font-medium text-gray-800 underline underline-offset-2" href="{{ route('guru.my-notes.show', $record) }}">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-5 py-4"><x-ui.pagination :paginator="$records" /></div>
        @else
            <div class="p-6">
                <x-ui.empty-state title="Belum ada catatan yang sesuai" description="Catatan yang Anda buat akan muncul di sini. Sesuaikan pencarian atau filter jika catatan yang dicari belum terlihat." />
            </div>
        @endif
    </x-ui.card>
@endsection
