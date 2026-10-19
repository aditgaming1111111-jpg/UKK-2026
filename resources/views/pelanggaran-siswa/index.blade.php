@extends($role === 'admin' ? 'layouts.admin' : 'layouts.guru')

@section('title', 'Riwayat Pelanggaran')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Riwayat Pelanggaran']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Riwayat Pelanggaran" description="Telusuri catatan kejadian, snapshot poin, status, dan guru pencatat.">
        @if ($role === 'guru')
            <x-ui.button :href="route('guru.violations.create')">Catat pelanggaran</x-ui.button>
        @endif
    </x-ui.page-heading>
@endsection

@section('content')
    <x-ui.card>
        <form method="GET" action="{{ route($routePrefix.'.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.search placeholder="Cari nama siswa atau NISN" :value="$filters['q'] ?? null" class="lg:col-span-2" />
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Tahun ajaran</span>
                <select name="tahun_ajaran_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua tahun ajaran</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected(($filters['tahun_ajaran_id'] ?? '') == $year->id)>{{ $year->nama_tahun_ajaran }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Kelas saat kejadian</span>
                <select name="kelas_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(($filters['kelas_id'] ?? '') == $class->id)>{{ $class->nama_kelas }} · {{ $class->tingkat }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Jenis pelanggaran</span>
                <select name="pelanggaran_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua jenis</option>
                    @foreach ($violations as $violation)
                        <option value="{{ $violation->id }}" @selected(($filters['pelanggaran_id'] ?? '') == $violation->id)>{{ $violation->kode }} · {{ $violation->nama_pelanggaran }}{{ $violation->status_aktif ? '' : ' (nonaktif)' }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Tanggal mulai</span>
                <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                <x-ui.form-error name="tanggal_mulai" />
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Tanggal akhir</span>
                <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                <x-ui.form-error name="tanggal_selesai" />
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Guru pencatat</span>
                <select name="guru_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua guru</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(($filters['guru_id'] ?? '') == $teacher->id)>{{ $teacher->nama }}{{ $teacher->status_aktif ? '' : ' (nonaktif)' }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Status</span>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                    <option value="dibatalkan" @selected(($filters['status'] ?? '') === 'dibatalkan')>Dibatalkan</option>
                </select>
            </label>
            <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-4">
                <x-ui.button type="submit">Terapkan filter</x-ui.button>
                <x-ui.button variant="secondary" :href="route($routePrefix.'.index')">Reset</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card :padding="false">
        @if ($records->isNotEmpty())
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr><th class="px-4 py-3">Tanggal kejadian</th><th class="px-4 py-3">Siswa</th><th class="px-4 py-3">Kelas / Tahun</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Guru</th><th class="px-4 py-3">Poin</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ $record->tanggal_pelanggaran->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm"><span class="block font-medium text-gray-900">{{ $record->penempatanKelas->siswa->nama }}</span><span class="text-xs text-gray-500">NISN {{ $record->penempatanKelas->siswa->nisn }}</span></td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $record->penempatanKelas->kelas->nama_kelas }}<span class="mt-1 block text-xs text-gray-500">{{ $record->penempatanKelas->tahunAjaran->nama_tahun_ajaran }}</span></td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $record->pelanggaran->nama_pelanggaran }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $record->guru->nama }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ number_format($record->poin_saat_dicatat, 0, ',', '.') }}</td>
                            <td class="px-4 py-3"><x-ui.badge :variant="$record->status === 'aktif' ? 'dark' : 'outline'">{{ $record->status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}</x-ui.badge></td>
                            <td class="whitespace-nowrap px-4 py-3 text-right"><a class="text-sm font-medium text-gray-800 underline underline-offset-2" href="{{ route($routePrefix.'.show', $record) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-5 py-4"><x-ui.pagination :paginator="$records" /></div>
        @else
            <div class="p-6"><x-ui.empty-state title="Catatan tidak ditemukan" description="Sesuaikan pencarian atau filter. Catatan yang dibatalkan tetap muncul jika statusnya dipilih." /></div>
        @endif
    </x-ui.card>
@endsection
