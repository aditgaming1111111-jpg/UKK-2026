@props(['role' => 'admin'])

<header
    class="sticky top-0 z-20 flex min-h-16 items-center justify-between gap-4 border-b border-gray-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="min-w-0">
        <p class="truncate text-xs font-medium text-gray-500">Ruang {{ $role === 'admin' ? 'Admin' : 'Guru' }}</p>
        <p class="truncate text-sm font-semibold">{{ auth()->user()?->nama ?? 'Pengguna' }}</p>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-gray-500 hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 motion-reduce:transition-none">
            <x-ui.icon name="logout" class="size-4" />
            <span class="hidden sm:inline">Keluar</span>
        </button>
    </form>
</header>
