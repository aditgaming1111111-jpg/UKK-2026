@extends('layouts.admin')
@section('title', 'Detail Kategori Pelanggaran')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Kategori Pelanggaran', 'url' => route('admin.violation-categories.index')], ['label' => $category->nama_kategori]]" />@endsection
@section('page-heading')<x-ui.page-heading title="{{ $category->nama_kategori }}" description="Detail kategori dan jenis pelanggaran terkait."><div class="flex flex-wrap gap-2"><x-ui.button variant="secondary" :href="route('admin.violation-categories.edit', $category)">Edit kategori</x-ui.button>@if($category->jenis_pelanggaran_count === 0)<x-ui.button variant="secondary" type="button" data-modal-open="delete-category">Hapus kategori</x-ui.button>@endif</div></x-ui.page-heading>@endsection
@section('content')
@if(session('success'))<x-ui.toast>{{ session('success') }}</x-ui.toast>@endif
@if($errors->has('pelanggaranKategori'))<x-ui.error-state title="Kategori tidak dapat dihapus" :description="$errors->first('pelanggaranKategori')" />@endif
<x-ui.card>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <x-ui.badge :variant="$category->status_aktif ? 'dark' : 'outline'">
                {{ $category->status_aktif ? 'Aktif' : 'Nonaktif' }}
            </x-ui.badge>
            <p class="mt-4 whitespace-pre-line text-sm text-gray-600">
                {{ $category->deskripsi ?: 'Tidak ada deskripsi.' }}
            </p>
        </div>
        <form id="category-status-form" method="POST" data-modal-confirm-form
            action="{{ route('admin.violation-categories.status', $category) }}">
            @csrf
            @method('PATCH')
            <x-ui.button variant="secondary" type="button" data-modal-open="category-status-confirmation">
                {{ $category->status_aktif ? 'Nonaktifkan kategori' : 'Aktifkan kategori' }}
            </x-ui.button>
        </form>
    </div>
    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <x-ui.stat-card label="Seluruh jenis" :value="$category->jenis_pelanggaran_count" />
        <x-ui.stat-card label="Jenis aktif" :value="$category->active_violations_count" />
    </div>
    <x-ui.modal id="category-status-confirmation"
        :title="$category->status_aktif ? 'Nonaktifkan kategori?' : 'Aktifkan kategori kembali?'"
        :description="$category->status_aktif ? 'Nonaktifkan kategori dan jenis aktif di dalamnya? Riwayat tetap disimpan.' : 'Aktifkan kembali kategori? Jenis pelanggaran tetap nonaktif.'">
        <div class="flex justify-end gap-2">
            <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
            <x-ui.button type="submit" form="category-status-form">
                {{ $category->status_aktif ? 'Nonaktifkan kategori' : 'Aktifkan kategori' }}
            </x-ui.button>
        </div>
    </x-ui.modal>
    @if($category->jenis_pelanggaran_count === 0)
        <x-ui.modal id="delete-category" title="Hapus kategori?" description="Kategori ini belum memiliki jenis pelanggaran.">
            <form method="POST" action="{{ route('admin.violation-categories.destroy', $category) }}">@csrf @method('DELETE')
                <div class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Hapus permanen</x-ui.button></div>
            </form>
        </x-ui.modal>
    @else
        <p class="mt-4 text-sm text-gray-500">Kategori masih memiliki jenis pelanggaran. Nonaktifkan kategori untuk mempertahankan data master dan histori.</p>
    @endif
</x-ui.card>
<x-ui.card :padding="false"><div class="flex items-center justify-between border-b border-gray-200 px-5 py-4"><h2 class="font-semibold text-gray-900">Jenis dalam kategori</h2><x-ui.button size="sm" :href="route('admin.violations.create')">Tambah jenis</x-ui.button></div>@if($violations->isNotEmpty())<x-ui.table><thead class="bg-gray-50 text-left text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Kode</th><th class="px-5 py-3">Jenis</th><th class="px-5 py-3">Poin</th><th class="px-5 py-3">Catatan historis</th><th class="px-5 py-3">Status</th></tr></thead><tbody class="divide-y divide-gray-100">@foreach($violations as $violation)<tr><td class="px-5 py-4 font-mono text-sm">{{ $violation->kode }}</td><td class="px-5 py-4"><a class="font-medium underline" href="{{ route('admin.violations.show', $violation) }}">{{ $violation->nama_pelanggaran }}</a></td><td class="px-5 py-4">{{ $violation->poin }}</td><td class="px-5 py-4">{{ $violation->catatan_siswa_count }}</td><td class="px-5 py-4"><x-ui.badge :variant="$violation->status_aktif ? 'dark' : 'outline'">{{ $violation->status_aktif ? 'Aktif' : 'Nonaktif' }}</x-ui.badge></td></tr>@endforeach</tbody></x-ui.table><div class="border-t border-gray-200 px-5 py-4"><x-ui.pagination :paginator="$violations" /></div>@else<div class="p-5"><x-ui.empty-state title="Belum ada jenis pelanggaran" description="Jenis pelanggaran yang ditambahkan ke kategori ini akan tampil di sini."/></div>@endif</x-ui.card>
@endsection
