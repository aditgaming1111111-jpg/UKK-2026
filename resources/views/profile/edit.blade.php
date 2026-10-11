@extends($user->role === 'admin' ? 'layouts.admin' : 'layouts.guru', ['hasActiveWaliKelas' => $hasActiveWaliKelas])

@section('title', 'Profil Akun')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Profil']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Profil Akun" description="Kelola informasi akun dan keamanan kata sandi Anda." />
@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 xl:grid-cols-2">
        <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5">
                <h2 class="font-semibold text-gray-950">Informasi akun</h2>
                <p class="mt-1 text-sm text-gray-500">Role akun: {{ $user->role === 'admin' ? 'Admin' : 'Guru' }}.</p>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <x-ui.input label="Nama akun" name="nama" :value="$user->nama" autocomplete="name" required />
                <x-ui.input label="Email login" name="email" type="email" :value="$user->email" autocomplete="email" required />
                <div class="flex justify-end">
                    <x-ui.button type="submit">Simpan profil</x-ui.button>
                </div>
            </form>

            @if ($user->role === 'guru' && $user->guru)
                <dl class="mt-6 grid gap-4 border-t border-gray-200 pt-5 sm:grid-cols-2">
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">NIP</dt><dd class="mt-1 text-sm font-medium">{{ $user->guru->nip }}</dd></div>
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Status Guru</dt><dd class="mt-1 text-sm font-medium">{{ $user->guru->status_aktif ? 'Aktif' : 'Nonaktif' }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Email identitas Guru</dt><dd class="mt-1 text-sm font-medium">{{ $user->guru->email }}</dd><p class="mt-1 text-xs text-gray-500">Perubahan identitas resmi dikelola Admin pada Data Guru.</p></div>
                </dl>
            @endif
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5">
                <h2 class="font-semibold text-gray-950">Ganti kata sandi</h2>
                <p class="mt-1 text-sm text-gray-500">Masukkan kata sandi saat ini dan konfirmasi kata sandi baru.</p>
            </div>
            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <x-ui.input label="Kata sandi saat ini" name="current_password" type="password" autocomplete="current-password" required />
                <x-ui.input label="Kata sandi baru" name="password" type="password" autocomplete="new-password" hint="Minimal 8 karakter." required />
                <x-ui.input label="Konfirmasi kata sandi baru" name="password_confirmation" type="password" autocomplete="new-password" required />
                <div class="flex justify-end">
                    <x-ui.button type="submit" variant="secondary">Perbarui kata sandi</x-ui.button>
                </div>
            </form>
        </section>
    </div>
@endsection
