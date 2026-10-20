@props(['role' => 'admin'])

<header
    class="sticky top-0 z-20 flex min-h-16 items-center justify-end gap-4 border-b border-gray-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
    @php($user = auth()->user())
    @php($userName = $user?->nama ?? 'Pengguna')
    <div class="flex min-w-0 items-center gap-3">
        <span class="min-w-0 text-right">
            <span class="block text-xs text-gray-500">{{ $role === 'admin' ? 'Admin' : 'Guru' }}</span>
            <span class="block truncate text-sm font-semibold text-gray-900">{{ $userName }}</span>
        </span>
        <span
            class="grid size-9 shrink-0 place-items-center rounded-full border border-gray-300 bg-gray-100 text-xs font-semibold text-gray-700"
            aria-hidden="true">{{ str($userName)->substr(0, 1)->upper() }}</span>
    </div>
</header>
