<!DOCTYPE html>
<html lang="id" class="app-scrollbar-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $role }} — SekolahKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <main class="mx-auto max-w-3xl px-4 py-12">
        <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500">SekolahKu</p>
            <h1 class="mt-3 text-2xl font-bold">Tujuan autentikasi {{ $role }}</h1>
            <p class="mt-2 text-sm text-gray-600">Halaman sementara untuk memverifikasi akses berdasarkan role.</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button type="submit"
                    class="min-h-11 rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold transition hover:bg-gray-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 motion-reduce:transition-none">Keluar</button>
            </form>
        </section>
    </main>
</body>

</html>
