@extends('layouts.admin')

@section('title', 'Laporan Pelanggaran')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Laporan']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Laporan Pelanggaran" description="Rekap catatan berdasarkan tahun ajaran dan filter kejadian." />
@endsection

@section('content')
    @php($filterControlClasses = 'mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200')
    <x-ui.card :padding="false" class="p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid items-end gap-x-4 gap-y-3 sm:grid-cols-2 lg:grid-cols-12">
            <label class="block space-y-1.5 text-sm font-medium text-gray-700 lg:col-span-3">
                <span>Tahun ajaran</span>
                <select name="tahun_ajaran_id" class="{{ $filterControlClasses }}">
                    <option value="">Semua tahun ajaran</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected((string) ($filters['tahun_ajaran_id'] ?? '') === (string) $year->id)>{{ $year->nama_tahun_ajaran }}{{ $year->status_aktif ? ' (aktif)' : '' }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block space-y-1.5 text-sm font-medium text-gray-700 lg:col-span-3">
                <span>Kelas saat kejadian</span>
                <select name="kelas_id" class="{{ $filterControlClasses }}">
                    <option value="">Semua kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected((string) ($filters['kelas_id'] ?? '') === (string) $class->id)>{{ $class->nama_kelas }} · Tingkat {{ $class->tingkat }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block space-y-1.5 text-sm font-medium text-gray-700 lg:col-span-3">
                <span>Nama atau NISN siswa</span>
                <input name="q" value="{{ $filters['q'] ?? '' }}" class="{{ $filterControlClasses }} placeholder:text-gray-400" placeholder="Cari siswa...">
            </label>
            <label class="block space-y-1.5 text-sm font-medium text-gray-700 lg:col-span-3">
                <span>Jenis pelanggaran</span>
                <select name="pelanggaran_id" class="{{ $filterControlClasses }}">
                    <option value="">Semua jenis</option>
                    @foreach ($violations as $violation)
                        <option value="{{ $violation->id }}" @selected((string) ($filters['pelanggaran_id'] ?? '') === (string) $violation->id)>{{ $violation->kode }} · {{ $violation->nama_pelanggaran }}{{ $violation->status_aktif ? '' : ' (nonaktif)' }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block space-y-1.5 text-sm font-medium text-gray-700 lg:col-span-2">
                <span>Tanggal kejadian mulai</span>
                <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="{{ $filterControlClasses }}">
            </label>
            <label class="block space-y-1.5 text-sm font-medium text-gray-700 lg:col-span-2">
                <span>Tanggal kejadian sampai</span>
                <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="{{ $filterControlClasses }}">
            </label>
            <label class="block space-y-1.5 text-sm font-medium text-gray-700 lg:col-span-3">
                <span>Status catatan</span>
                <select name="status" class="{{ $filterControlClasses }}">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                    <option value="dibatalkan" @selected(($filters['status'] ?? '') === 'dibatalkan')>Dibatalkan</option>
                </select>
            </label>
            <div class="flex flex-wrap items-center gap-2 lg:col-span-5 lg:justify-end">
                <x-ui.button type="submit">Terapkan filter</x-ui.button>
                <x-ui.button variant="secondary" :href="route('admin.reports.index')">Reset</x-ui.button>
            </div>
        </form>
        @if ($errors->any())
            <div role="alert" class="mt-3 rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-800">{{ $errors->first() }}</div>
        @endif
    </x-ui.card>

    <section class="grid gap-4 sm:grid-cols-3">
        <x-ui.stat-card label="Catatan aktif" :value="$summary['active_records']" />
        <x-ui.stat-card label="Total poin aktif" :value="$summary['active_points']" />
        <x-ui.stat-card label="Catatan dibatalkan" :value="$summary['cancelled_records']" />
    </section>

    <x-ui.card :padding="false">
        <div class="flex flex-col gap-3 border-b border-gray-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div>
                <h2 class="text-base font-semibold text-gray-950">Detail catatan</h2>
                <p class="mt-1 text-sm text-gray-500">Kelas ditampilkan sesuai penempatan saat kejadian.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-ui.button size="sm" variant="secondary" :href="route('admin.reports.print', request()->query())" target="_blank">Tampilan cetak</x-ui.button>
                <x-ui.button size="sm" :href="route('admin.reports.pdf', request()->query())">Ekspor PDF</x-ui.button>
            </div>
        </div>
        @if ($records->isEmpty())
            <div class="p-4 sm:p-5">
                <x-ui.empty-state title="Tidak ada catatan" description="Belum ada catatan yang sesuai dengan filter ini." />
            </div>
        @else
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Siswa</th>
                        <th class="px-4 py-3 font-medium">Kelas kejadian</th>
                        <th class="px-4 py-3 font-medium">Tahun ajaran</th>
                        <th class="px-4 py-3 font-medium">Jenis</th>
                        <th class="px-4 py-3 font-medium">Guru</th>
                        <th class="px-4 py-3 text-right font-medium">Poin</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ $record->tanggal_pelanggaran->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="block font-medium text-gray-900">{{ $record->penempatanKelas?->siswa?->nama ?? '—' }}</span>
                                <span class="block text-xs text-gray-500">{{ $record->penempatanKelas?->siswa?->nisn ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $record->penempatanKelas?->kelas?->nama_kelas ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ $record->penempatanKelas?->tahunAjaran?->nama_tahun_ajaran ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $record->pelanggaran?->nama_pelanggaran ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $record->guru?->nama ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm tabular-nums text-gray-800">{{ $record->poin_saat_dicatat }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$record->status === 'aktif' ? 'dark' : 'outline'">{{ $record->status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}</x-ui.badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-4 py-4 sm:px-5"><x-ui.pagination :paginator="$records" /></div>
        @endif
    </x-ui.card>
@endsection
