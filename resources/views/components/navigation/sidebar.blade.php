@props(['role' => 'admin', 'hasActiveWaliKelas' => false])

@php
    $adminMenu = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'grid'],
        ['label' => 'Data Siswa', 'route' => 'admin.students.index', 'icon' => 'users'],
        ['label' => 'Data Guru', 'route' => 'admin.teachers.index', 'icon' => 'user'],
        ['label' => 'Data Kelas', 'route' => 'admin.classes.index', 'icon' => 'grid'],
        ['label' => 'Tahun Ajaran', 'route' => 'admin.academic-years.index', 'icon' => 'calendar'],
        ['label' => 'Wali Kelas', 'route' => 'admin.homeroom-teachers.index', 'icon' => 'user-check'],
        ['label' => 'Kategori Pelanggaran', 'route' => 'admin.violation-categories.index', 'icon' => 'tag'],
        ['label' => 'Jenis Pelanggaran', 'route' => 'admin.violations.index', 'icon' => 'warning'],
        ['label' => 'Data Pelanggaran', 'route' => 'admin.student-violations.index', 'icon' => 'document'],
        ['label' => 'Laporan', 'route' => 'admin.reports.index', 'icon' => 'chart'],
        ['label' => 'Log Aktivitas', 'route' => 'admin.audit-logs.index', 'icon' => 'clock'],
        ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'user'],
    ];

    $guruMenu = [
        ['label' => 'Dashboard', 'route' => 'guru.dashboard', 'icon' => 'grid'],
        ['label' => 'Data Siswa', 'route' => 'guru.students.index', 'icon' => 'users'],
        ['label' => 'Input Pelanggaran', 'route' => 'guru.violations.create', 'icon' => 'plus'],
        ['label' => 'Riwayat Pelanggaran', 'route' => 'guru.violations.index', 'icon' => 'clock'],
        ['label' => 'Catatan Saya', 'route' => 'guru.my-notes.index', 'active' => 'guru.my-notes.*', 'icon' => 'document'],
        ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'user'],
    ];

    $menuItems = $role === 'admin' ? $adminMenu : $guruMenu;

    $roleTitle = $role === 'admin' ? 'Administrator' : 'Guru';
@endphp

<div {{ $attributes->merge(['class' => 'flex h-full flex-col bg-white']) }}>
    <a href="/" class="flex h-16 shrink-0 items-center gap-3 border-b border-gray-200 px-5"
        aria-label="SekolahKu, halaman utama">
        <span class="grid size-9 place-items-center rounded-xl bg-gray-900 text-sm font-bold text-white">S</span>
        <span>
            <span class="block text-sm font-bold tracking-tight">SekolahKu</span>
            <span class="block text-xs text-gray-500">Administrasi sekolah</span>
        </span>
    </a>

    <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4">
        <span
            class="grid size-9 place-items-center rounded-full border border-gray-300 bg-gray-100 text-xs font-semibold text-gray-700">
            {{ str($roleTitle)->substr(0, 1) }}
        </span>
        <span class="min-w-0">
            <span class="block truncate text-sm font-medium">{{ $roleTitle }}</span>
            <span class="block text-xs text-gray-500">Ruang kerja</span>
        </span>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4" aria-label="Navigasi {{ $roleTitle }}">
        <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-gray-400">Menu utama</p>
        @foreach ($menuItems as $item)
            @php($available = \Illuminate\Support\Facades\Route::has($item['route']))
            @php($active = $available && request()->routeIs($item['active'] ?? $item['route']))

            @if ($available)
                <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition motion-reduce:transition-none {{ $active ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-950 focus-visible:bg-gray-100' }} focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                    <x-ui.icon :name="$item['icon']" class="size-[18px] shrink-0" />
                    <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                </a>
            @else
                <span
                    class="flex cursor-default items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-400"
                    aria-disabled="true">
                    <x-ui.icon :name="$item['icon']" class="size-[18px] shrink-0" />
                    <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                    <span class="text-[10px] font-medium uppercase tracking-wide">Segera</span>
                </span>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-gray-200 px-5 py-3 text-xs text-gray-500">SekolahKu <span aria-hidden="true">·</span>
        Sistem pelanggaran siswa</div>
</div>
