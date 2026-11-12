@extends('layouts.admin')

{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Tambah Guru')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Data Guru', 'url' => route('admin.teachers.index')], ['label' => 'Tambah Guru']]" />@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')<x-ui.page-heading title="Tambah Guru"
    description="Buat identitas Guru beserta akun login yang langsung terhubung." />@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="POST" action="{{ route('admin.teachers.store') }}">
            @csrf
            <h2 class="text-base font-semibold text-gray-950">Identitas dan akun Guru</h2>
            <div class="mt-4 grid gap-5 sm:grid-cols-2">
                <x-ui.select label="Status kepegawaian" name="status_kepegawaian" required>
                    <option value="">Pilih status kepegawaian</option>
                    @foreach (\App\Models\Guru::STATUS_KEPEGAWAIAN as $employmentStatus)
                        <option value="{{ $employmentStatus }}" @selected(old('status_kepegawaian') === $employmentStatus)>{{ $employmentStatus }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.input label="NIP" name="nip" inputmode="numeric" maxlength="18"
                    hint="Wajib untuk PNS. Untuk PPPK dan Non-PNS opsional; jika diisi harus tepat 18 digit angka." />
                <x-ui.input label="Nama lengkap" name="nama" required />
                <div class="sm:col-span-2"><x-ui.input label="Email" name="email" type="email"
                        hint="Email ini digunakan untuk identitas Guru sekaligus login." required /></div>
            </div>
            <div class="mt-7 border-t border-gray-200 pt-6">
                <h2 class="text-base font-semibold text-gray-950">Akun login</h2>
                <p class="mt-1 text-sm text-gray-500">Akun dibuat dengan role Guru dan menggunakan email di atas.</p>
                <div class="mt-4 grid gap-5 sm:grid-cols-2"><x-ui.input label="Kata sandi login" name="password_login" type="password"
                        autocomplete="new-password" hint="Minimal 8 karakter." required /><x-ui.input
                        label="Konfirmasi kata sandi" name="password_login_confirmation" type="password"
                        autocomplete="new-password" required /></div>
            </div>
            <div class="mt-6 flex justify-end gap-3"><x-ui.button variant="secondary"
                    :href="route('admin.teachers.index')">Batal</x-ui.button><x-ui.button type="submit">Simpan Guru</x-ui.button></div>
        </form>
    </x-ui.card>
@endsection
