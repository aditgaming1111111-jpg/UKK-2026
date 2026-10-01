<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SekolahKu') — SekolahKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    @php($role = $role ?? 'admin')
    @php($hasActiveWaliKelas = $hasActiveWaliKelas ?? false)

    <div class="min-h-screen md:flex">
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 border-r border-gray-200 bg-white md:block">
            <x-navigation.sidebar :role="$role" :has-active-wali-kelas="$hasActiveWaliKelas" />
        </aside>

        <div class="min-w-0 flex-1 md:pl-64">
            <div class="border-b border-gray-200 bg-white p-3 md:hidden">
                <details class="group">
                    <summary
                        class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                        <span class="flex items-center gap-3"><span
                                class="grid size-8 place-items-center rounded-lg bg-gray-900 text-sm font-bold text-white">S</span>Menu
                            {{ $role === 'admin' ? 'Admin' : 'Guru' }}</span>
                        <x-ui.icon name="chevron-down" class="size-4 transition-transform group-open:rotate-180" />
                    </summary>
                    <div class="mt-3 max-h-[70vh] overflow-y-auto rounded-xl border border-gray-200">
                        <x-navigation.sidebar :role="$role" :has-active-wali-kelas="$hasActiveWaliKelas" />
                    </div>
                </details>
            </div>

            <x-navigation.topbar :role="$role" />

            <main class="mx-auto w-full max-w-[1600px] p-4 sm:p-6 lg:p-8">
                @yield('breadcrumbs')
                @yield('page-heading')
                <div class="space-y-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>
