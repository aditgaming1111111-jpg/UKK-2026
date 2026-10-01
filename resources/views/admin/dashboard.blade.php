@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Dashboard']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Dashboard Admin" description="Ringkasan aktivitas dan data pelanggaran SekolahKu.">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col gap-2 sm:flex-row sm:items-end">
            <label for="tahun_ajaran_id" class="space-y-1.5">
                <span class="block text-xs font-medium text-gray-600">Tahun ajaran</span>
                <select id="tahun_ajaran_id" name="tahun_ajaran_id"
                    class="min-w-52 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
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

    <section aria-label="Statistik sekolah" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.stat-card label="Siswa aktif" :value="number_format($stats['active_students'], 0, ',', '.')" note="Semua tahun ajaran" icon="users" />
        <x-ui.stat-card label="Guru aktif" :value="number_format($stats['active_teachers'], 0, ',', '.')" note="Akun guru aktif" icon="user-check" />
        <x-ui.stat-card label="Pelanggaran aktif" :value="number_format($stats['active_violations'], 0, ',', '.')" :note="$selectedAcademicYear?->nama_tahun_ajaran ?? 'Tahun ajaran belum tersedia'" icon="warning" />
        <x-ui.stat-card label="Total poin aktif" :value="number_format($stats['active_points'], 0, ',', '.')" note="Menggunakan snapshot poin" icon="chart" />
    </section>

    <p class="text-xs leading-5 text-gray-500">Pelanggaran dan poin dihitung dari catatan berstatus aktif pada penempatan
        kelas tahun ajaran yang dipilih. Poin memakai nilai saat pencatatan; catatan dibatalkan tidak ikut dihitung.</p>

    @if ($selectedAcademicYear === null)
        <x-ui.empty-state title="Tahun ajaran belum tersedia"
            description="Tambahkan tahun ajaran untuk melihat statistik, catatan, dan aktivitas terkait." />
    @endif

    <section aria-label="Akses cepat">
        <div class="mb-3 flex items-end justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-950">Akses cepat</h2>
                <p class="mt-1 text-sm text-gray-500">Buka bagian administrasi yang sering digunakan.</p>
            </div>
        </div>
        <div class="grid gap-3 sm:grid-cols-3">
            @foreach ($quickLinks as $quickLink)
                <x-ui.card class="flex items-center justify-between gap-3 !p-4">
                    <span class="text-sm font-semibold text-gray-800">{{ $quickLink['label'] }}</span>
                    @if (\Illuminate\Support\Facades\Route::has($quickLink['route']))
                        <x-ui.button size="sm" variant="secondary" :href="route($quickLink['route'])">Buka</x-ui.button>
                    @else
                        <x-ui.button size="sm" variant="secondary" disabled
                            title="Fitur tersedia pada tahap berikutnya">Segera</x-ui.button>
                    @endif
                </x-ui.card>
            @endforeach
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(18rem,0.8fr)]">
        <x-ui.card>
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-gray-950">Catatan pelanggaran terbaru</h2>
                    <p class="mt-1 text-sm text-gray-500">Riwayat pada
                        {{ $selectedAcademicYear?->nama_tahun_ajaran ?? 'tahun ajaran terpilih' }}.</p>
                </div>
                <span class="text-xs text-gray-500">Maksimal 8 catatan</span>
            </div>

            @if ($recentViolations->isNotEmpty())
                <div class="mt-5">
                    <x-ui.table>
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">Siswa / Kelas</th>
                                <th class="px-4 py-3 font-medium">Pelanggaran</th>
                                <th class="px-4 py-3 font-medium">Kejadian</th>
                                <th class="px-4 py-3 font-medium">Poin</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recentViolations as $violationRecord)
                                <tr class="align-top hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <p class="font-medium text-gray-900">
                                            {{ $violationRecord->penempatanKelas?->siswa?->nama ?? 'Siswa tidak tersedia' }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-gray-500">
                                            {{ $violationRecord->penempatanKelas?->kelas?->nama_kelas ?? 'Kelas tidak tersedia' }}
                                        </p>
                                    </td>
                                    <td class="min-w-44 px-4 py-3 text-gray-700">
                                        {{ $violationRecord->pelanggaran?->nama_pelanggaran ?? 'Jenis pelanggaran tidak tersedia' }}
                                        <p class="mt-1 text-xs text-gray-500">Dicatat
                                            {{ $violationRecord->tanggal_dicatat?->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                        {{ $violationRecord->tanggal_pelanggaran?->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-800">
                                        {{ number_format($violationRecord->poin_saat_dicatat, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3"><x-ui.badge
                                            :variant="$violationRecord->status === 'aktif' ? 'dark' : 'outline'">{{ $violationRecord->status === 'dibatalkan' ? 'Dibatalkan' : ucfirst($violationRecord->status) }}</x-ui.badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                </div>
            @else
                <div class="mt-5"><x-ui.empty-state title="Belum ada catatan"
                        description="Catatan pelanggaran untuk tahun ajaran ini akan tampil di sini." /></div>
            @endif
        </x-ui.card>

        <x-ui.card>
            <div>
                <h2 class="text-base font-semibold text-gray-950">Pelanggaran per kategori</h2>
                <p class="mt-1 text-sm text-gray-500">Jumlah catatan aktif dan poin snapshot.</p>
            </div>
            @if ($categorySummary->isNotEmpty())
                <ul class="mt-5 divide-y divide-gray-100">
                    @foreach ($categorySummary as $category)
                        <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-gray-800">{{ $category->nama_kategori }}</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ number_format($category->total_poin, 0, ',', '.') }} poin</p>
                            </div>
                            <x-ui.badge>{{ number_format($category->total_pelanggaran, 0, ',', '.') }} catatan</x-ui.badge>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="mt-5"><x-ui.empty-state title="Belum ada ringkasan"
                        description="Belum ada pelanggaran aktif untuk kategori pada tahun ajaran ini." /></div>
            @endif
        </x-ui.card>
    </div>

    <x-ui.card>
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-950">Aktivitas perubahan terbaru</h2>
                <p class="mt-1 text-sm text-gray-500">Log terkait catatan pada tahun ajaran terpilih.</p>
            </div><span class="text-xs text-gray-500">Maksimal 8 aktivitas</span>
        </div>
        @if ($recentActivities->isNotEmpty())
            <ul class="mt-5 divide-y divide-gray-100">
                @foreach ($recentActivities as $activity)
                    @php
                        $actionDescription = match ($activity->aksi) {
                            'tambah' => 'menambahkan catatan pelanggaran',
                            'edit' => 'mengoreksi catatan pelanggaran',
                            'batal' => 'membatalkan catatan pelanggaran',
                            default => $activity->aksi,
                        };
                    @endphp
                    <li
                        class="flex flex-col gap-1 py-3 first:pt-0 sm:flex-row sm:items-start sm:justify-between sm:gap-4 last:pb-0">
                        <div class="min-w-0">
                            <p class="text-sm text-gray-800"><span
                                    class="font-semibold">{{ $activity->pengguna?->nama ?? 'Pengguna tidak tersedia' }}</span>
                                {{ $actionDescription }} untuk <span
                                    class="font-medium">{{ $activity->catatanPelanggaran?->penempatanKelas?->siswa?->nama ?? 'siswa' }}</span>.
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $activity->catatanPelanggaran?->pelanggaran?->nama_pelanggaran ?? 'Jenis pelanggaran tidak tersedia' }}
                                @if ($activity->alasan)
                                    · Alasan: {{ \Illuminate\Support\Str::limit($activity->alasan, 100) }}
                                @endif
                            </p>
                        </div>
                        <time class="shrink-0 text-xs text-gray-500"
                            datetime="{{ $activity->created_at?->toIso8601String() }}">{{ $activity->created_at?->format('d/m/Y H:i') }}</time>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="mt-5"><x-ui.empty-state title="Belum ada aktivitas"
                    description="Perubahan catatan pelanggaran akan dicatat dan tampil di sini." /></div>
        @endif
    </x-ui.card>
@endsection
