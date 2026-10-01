@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Dashboard']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Dashboard Guru" :description="'Selamat datang, ' . $teacher->nama . '. Berikut ringkasan catatan Anda.'">
        <form method="GET" action="{{ route('guru.dashboard') }}" class="flex flex-col gap-2 sm:flex-row sm:items-end">
            <label for="tahun_ajaran_id" class="space-y-1.5">
                <span class="block text-xs font-medium text-gray-600">Tahun ajaran</span>
                <select id="tahun_ajaran_id" name="tahun_ajaran_id"
                    class="min-w-48 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    @forelse ($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}" @selected($selectedAcademicYear?->id === $academicYear->id)>
                            {{ $academicYear->nama_tahun_ajaran }}{{ $academicYear->status_aktif ? ' · Aktif' : '' }}
                        </option>
                    @empty
                        <option value="">Belum ada tahun ajaran</option>
                    @endforelse
                </select>
            </label>
            <x-ui.button type="submit" :disabled="$academicYears->isEmpty()">Terapkan</x-ui.button>
        </form>
    </x-ui.page-heading>
@endsection

@section('content')
    @if ($errors->has('tahun_ajaran_id'))
        <x-ui.error-state title="Filter tahun ajaran tidak valid" :description="$errors->first('tahun_ajaran_id')" />
    @endif

    <section aria-label="Pencatatan pelanggaran" class="overflow-hidden rounded-2xl bg-gray-900 text-white shadow-sm">
        <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-400">Aksi utama</p>
                <h2 class="mt-2 text-xl font-semibold tracking-tight sm:text-2xl">Catat pelanggaran siswa</h2>
                <p class="mt-2 text-sm leading-6 text-gray-300">Buat catatan baru dengan memilih siswa dan jenis
                    pelanggaran. Nilai poin disimpan sebagai snapshot saat pencatatan.</p>
            </div>
            @if (\Illuminate\Support\Facades\Route::has('guru.violations.create'))
                <x-ui.button variant="secondary" :href="route('guru.violations.create')" class="shrink-0">Mulai mencatat <x-ui.icon name="plus"
                        class="size-4" /></x-ui.button>
            @else
                <x-ui.button variant="secondary" disabled title="Form pencatatan tersedia pada tahap berikutnya"
                    class="shrink-0">Mulai mencatat <x-ui.icon name="plus" class="size-4" /></x-ui.button>
            @endif
        </div>
    </section>

    @if ($selectedAcademicYear === null)
        <x-ui.empty-state title="Tahun ajaran belum tersedia"
            description="Statistik catatan akan muncul setelah tahun ajaran dibuat." />
    @endif

    <section aria-label="Statistik catatan saya" class="grid gap-4 sm:grid-cols-3">
        <x-ui.stat-card label="Catatan dibuat" :value="number_format($stats['total_records'], 0, ',', '.')" :note="$selectedAcademicYear?->nama_tahun_ajaran ?? 'Tahun ajaran belum tersedia'" icon="document" />
        <x-ui.stat-card label="Masih aktif" :value="number_format($stats['active_records'], 0, ',', '.')" note="Catatan yang ikut perhitungan poin" icon="check" />
        <x-ui.stat-card label="Dibatalkan" :value="number_format($stats['cancelled_records'], 0, ',', '.')" note="Tetap tersimpan dalam riwayat" icon="warning" />
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(18rem,0.8fr)]">
        <x-ui.card>
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-gray-950">Aktivitas pencatatan terbaru</h2>
                    <p class="mt-1 text-sm text-gray-500">Catatan yang Anda buat pada tahun ajaran terpilih.</p>
                </div>
                <span class="text-xs text-gray-500">Maksimal 6 catatan</span>
            </div>

            @if ($recentRecords->isNotEmpty())
                <ul class="mt-5 divide-y divide-gray-100">
                    @foreach ($recentRecords as $record)
                        <li class="flex flex-col gap-3 py-4 first:pt-0 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-900">
                                    {{ $record->penempatanKelas?->siswa?->nama ?? 'Siswa tidak tersedia' }}</p>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ $record->pelanggaran?->nama_pelanggaran ?? 'Jenis pelanggaran tidak tersedia' }}</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $record->penempatanKelas?->kelas?->nama_kelas ?? 'Kelas tidak tersedia' }} <span
                                        aria-hidden="true">·</span> Kejadian
                                    {{ $record->tanggal_pelanggaran?->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-3 sm:flex-col sm:items-end sm:gap-2">
                                <span
                                    class="text-sm font-semibold text-gray-800">{{ number_format($record->poin_saat_dicatat, 0, ',', '.') }}
                                    poin</span>
                                <x-ui.badge
                                    :variant="$record->status === 'aktif' ? 'dark' : 'outline'">{{ $record->status === 'dibatalkan' ? 'Dibatalkan' : ucfirst($record->status) }}</x-ui.badge>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="mt-5"><x-ui.empty-state title="Belum ada catatan"
                        description="Catatan pelanggaran yang Anda buat akan tampil di sini." /></div>
            @endif
        </x-ui.card>

        <div class="space-y-6">
            @if ($homeroomAssignment !== null)
                <x-ui.card>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-gray-500">Penugasan aktif</p>
                            <h2 class="mt-2 text-lg font-semibold text-gray-950">Rekap Kelas Saya</h2>
                        </div>
                        <span class="grid size-10 place-items-center rounded-xl bg-gray-100 text-gray-700"><x-ui.icon
                                name="chart" class="size-5" /></span>
                    </div>
                    <p class="mt-4 text-2xl font-semibold tracking-tight text-gray-900">
                        {{ $homeroomAssignment->kelas?->nama_kelas ?? 'Kelas' }}</p>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $homeroomAssignment->tahunAjaran?->nama_tahun_ajaran ?? $selectedAcademicYear->nama_tahun_ajaran }}
                    </p>
                    <div class="mt-5">
                        @if (\Illuminate\Support\Facades\Route::has('guru.class-recap.index'))
                            <x-ui.button variant="secondary" :href="route('guru.class-recap.index', [
                                'tahun_ajaran_id' => $selectedAcademicYear->id,
                            ])" class="w-full">Buka rekap
                                kelas</x-ui.button>
                        @else
                            <x-ui.button variant="secondary" disabled title="Halaman rekap tersedia pada tahap berikutnya"
                                class="w-full">Rekap segera tersedia</x-ui.button>
                        @endif
                    </div>
                </x-ui.card>
            @endif

            <x-ui.card>
                <div>
                    <h2 class="text-base font-semibold text-gray-950">Akses lainnya</h2>
                    <p class="mt-1 text-sm text-gray-500">Temukan siswa dan tinjau catatan Anda.</p>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach ($quickLinks as $quickLink)
                        @if (\Illuminate\Support\Facades\Route::has($quickLink['route']))
                            <x-ui.button variant="secondary" :href="route($quickLink['route'])"
                                class="w-full justify-between">{{ $quickLink['label'] }}<x-ui.icon name="chevron-down"
                                    class="size-4 -rotate-90" /></x-ui.button>
                        @else
                            <x-ui.button variant="secondary" disabled title="Fitur tersedia pada tahap berikutnya"
                                class="w-full justify-between">{{ $quickLink['label'] }}<span
                                    class="text-[10px] font-medium uppercase tracking-wide">Segera</span></x-ui.button>
                        @endif
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
