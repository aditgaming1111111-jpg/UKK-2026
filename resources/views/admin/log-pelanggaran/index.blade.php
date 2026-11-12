@extends('layouts.admin')

{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Log Aktivitas')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Log Aktivitas']]" />
@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')
    <x-ui.page-heading title="Log Aktivitas" description="Riwayat aktivitas pengguna dan perubahan pada aplikasi SekolahKu." />
@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <x-ui.search placeholder="Cari pengguna, aktivitas, deskripsi, atau IP" :value="$search" class="flex-1" />
            <x-ui.button type="submit">Cari</x-ui.button>
        </form>
    </x-ui.card>

    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card :padding="false">
        @if ($logs->isNotEmpty())
            {{-- Tabel berikut merangkum data dalam susunan kolom yang mudah dipindai. --}}
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Pengguna</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Aktivitas</th>
                        <th class="px-5 py-3">Deskripsi</th>
                        <th class="px-5 py-3">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-5 py-4 text-sm text-gray-800">{{ $log->pengguna->nama }}</td>
                            <td class="px-5 py-4"><x-ui.badge variant="outline">{{ ucfirst($log->pengguna->role) }}</x-ui.badge></td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ $log->aktivitas }}</td>
                            <td class="max-w-lg px-5 py-4 text-sm text-gray-700">{{ $log->deskripsi }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $log->ip_address ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-5 py-4"><x-ui.pagination :paginator="$logs" /></div>
        @else
            <div class="p-6"><x-ui.empty-state title="Belum ada log aktivitas" description="Login dan perubahan data yang berhasil akan ditampilkan di sini." /></div>
        @endif
    </x-ui.card>
@endsection
