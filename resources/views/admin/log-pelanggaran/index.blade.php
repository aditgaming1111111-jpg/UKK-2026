@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Log Aktivitas']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Log Aktivitas" description="Audit baca-saja untuk pencatatan, koreksi, dan pembatalan catatan pelanggaran." />
@endsection

@section('content')
    <x-ui.card>
        <form method="GET" class="flex flex-col gap-3 sm:flex-row">
            <x-ui.search placeholder="Cari nama siswa, NISN, atau pengguna" :value="$search" class="flex-1" />
            <x-ui.button type="submit">Cari</x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.card :padding="false">
        @if ($logs->isNotEmpty())
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500"><tr><th class="px-5 py-3">Waktu</th><th class="px-5 py-3">Pengguna</th><th class="px-5 py-3">Aksi</th><th class="px-5 py-3">Siswa / Catatan</th><th class="px-5 py-3">Alasan</th><th class="px-5 py-3"></th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($logs as $log)
                        <tr><td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $log->created_at->format('d/m/Y H:i:s') }}</td><td class="px-5 py-4 text-sm text-gray-800">{{ $log->pengguna->nama }}</td><td class="px-5 py-4"><x-ui.badge variant="outline">{{ ['tambah' => 'Tambah', 'edit' => 'Koreksi', 'batal' => 'Pembatalan'][$log->aksi] ?? ucfirst($log->aksi) }}</x-ui.badge></td><td class="px-5 py-4 text-sm"><span class="block font-medium text-gray-900">{{ $log->catatanPelanggaran->penempatanKelas->siswa->nama }}</span><span class="text-xs text-gray-500">{{ $log->catatanPelanggaran->pelanggaran->nama_pelanggaran }} · {{ $log->catatanPelanggaran->tanggal_pelanggaran->format('d/m/Y') }}</span></td><td class="max-w-xs px-5 py-4 text-sm text-gray-700">{{ $log->alasan ?: 'Pencatatan awal' }}</td><td class="px-5 py-4 text-right"><a class="text-sm font-medium underline" href="{{ route('admin.student-violations.show', $log->catatanPelanggaran) }}">Buka catatan</a></td></tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-5 py-4"><x-ui.pagination :paginator="$logs" /></div>
        @else
            <div class="p-6"><x-ui.empty-state title="Belum ada log aktivitas" description="Aksi pencatatan dan perubahan catatan akan ditampilkan di sini." /></div>
        @endif
    </x-ui.card>
@endsection
