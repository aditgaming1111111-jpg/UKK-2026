<!DOCTYPE html>
<html lang="id" class="app-scrollbar-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SekolahKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-12">
        <section class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500">SekolahKu</p>
                <h1 class="mt-3 text-2xl font-bold tracking-tight">Masuk ke akun Anda</h1>
                <p class="mt-2 text-sm text-gray-600">Sistem Informasi Pelanggaran Siswa</p>
            </div>

            @if (session('status'))
                <p class="mb-4 rounded-lg bg-gray-100 px-4 py-3 text-sm text-gray-700" role="status">
                    {{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="min-h-11 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-gray-700 focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 @error('email') border-gray-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-gray-700" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium">Kata sandi</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="min-h-11 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-gray-700 focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 @error('password') border-gray-500 @enderror">
                    @error('password')
                        <p class="mt-2 text-sm text-gray-700" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="min-h-11 w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 motion-reduce:transition-none">
                    Masuk
                </button>
            </form>
        </section>
    </main>
</body>

</html>
