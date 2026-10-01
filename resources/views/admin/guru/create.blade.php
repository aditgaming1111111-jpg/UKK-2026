@extends('layouts.admin')

@section('title', 'Tambah Guru')

@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Data Guru', 'url' => route('admin.teachers.index')], ['label' => 'Tambah Guru']]" />@endsection

@section('page-heading')<x-ui.page-heading title="Tambah Guru"
    description="Buat identitas Guru beserta akun login yang langsung terhubung." />@endsection

@section('content')
    <x-ui.card>
        <form method="POST" action="{{ route('admin.teachers.store') }}">
            @csrf
            <h2 class="text-base font-semibold text-gray-950">Identitas Guru</h2>
            <div class="mt-4 grid gap-5 sm:grid-cols-2"><x-ui.input label="NIP" name="nip" required /><x-ui.input
                    label="Nama lengkap" name="nama" required />
                <div class="sm:col-span-2"><x-ui.input label="Email identitas Guru" name="email" type="email"
                        hint="Email kontak Guru, terpisah dari email untuk login." required /></div>
            </div>
            <div class="mt-7 border-t border-gray-200 pt-6">
                <h2 class="text-base font-semibold text-gray-950">Akun login</h2>
                <p class="mt-1 text-sm text-gray-500">Sesuai relasi database, setiap identitas Guru harus terhubung ke satu
                    akun dengan role Guru.</p>
                <div class="mt-4 grid gap-5 sm:grid-cols-2"><x-ui.input label="Email login" name="email_login"
                        type="email" required /><x-ui.input label="Kata sandi login" name="password_login" type="password"
                        autocomplete="new-password" hint="Minimal 8 karakter." required /><x-ui.input
                        label="Konfirmasi kata sandi" name="password_login_confirmation" type="password"
                        autocomplete="new-password" required /></div>
            </div>
            <div class="mt-6 flex justify-end gap-3"><x-ui.button variant="secondary"
                    :href="route('admin.teachers.index')">Batal</x-ui.button><x-ui.button type="submit">Simpan Guru</x-ui.button></div>
        </form>
    </x-ui.card>
@endsection
