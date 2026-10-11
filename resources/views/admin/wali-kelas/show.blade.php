@extends('layouts.admin')
@section('title', 'Detail Penugasan Wali Kelas')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Wali Kelas', 'url' => route('admin.homeroom-teachers.index')], ['label' => 'Detail']]" />@endsection
@section('page-heading')<x-ui.page-heading title="Detail Penugasan" description="Riwayat penugasan ini tidak dihapus ketika masa tugas berakhir." />@endsection
@section('content')
@if(session('success'))<x-ui.toast>{{ session('success') }}</x-ui.toast>@endif
@if($errors->any())<x-ui.error-state title="Penugasan belum dapat diubah" :description="$errors->first()"/>@endif
<x-ui.card><dl class="grid gap-5 sm:grid-cols-2"><div><dt class="text-sm text-gray-500">Guru</dt><dd class="mt-1 font-medium">{{ $assignment->guru->nama }} <span class="text-sm text-gray-500">({{ $assignment->guru->nip }})</span></dd></div><div><dt class="text-sm text-gray-500">Kelas</dt><dd class="mt-1 font-medium">{{ $assignment->kelas->nama_kelas }} · {{ $assignment->kelas->tingkat }}</dd></div><div><dt class="text-sm text-gray-500">Tahun ajaran</dt><dd class="mt-1 font-medium">{{ $assignment->tahunAjaran->nama_tahun_ajaran }}</dd></div><div><dt class="text-sm text-gray-500">Status</dt><dd class="mt-1"><x-ui.badge>{{ $assignment->status_aktif && !$assignment->tanggal_selesai ? 'Aktif' : 'Selesai' }}</x-ui.badge></dd></div><div><dt class="text-sm text-gray-500">Tanggal mulai</dt><dd class="mt-1">{{ $assignment->tanggal_mulai->format('d F Y') }}</dd></div><div><dt class="text-sm text-gray-500">Tanggal selesai</dt><dd class="mt-1">{{ $assignment->tanggal_selesai?->format('d F Y') ?? 'Belum diakhiri' }}</dd></div></dl>
@if ($assignment->status_aktif && !$assignment->tanggal_selesai)
    <form id="close-homeroom-assignment" method="POST" data-modal-confirm-form
        action="{{ route('admin.homeroom-teachers.close', $assignment) }}"
        class="mt-8 border-t border-gray-200 pt-6">
        @csrf
        @method('PATCH')
        <x-ui.input label="Tanggal selesai" name="tanggal_selesai" type="date"
            :value="old('tanggal_selesai', now()->toDateString())" required />
        <div class="mt-4 flex justify-end">
            <x-ui.button type="button" variant="secondary" data-modal-open="close-assignment-confirmation">
                Akhiri penugasan
            </x-ui.button>
        </div>
    </form>
    <x-ui.modal id="close-assignment-confirmation" title="Akhiri penugasan wali kelas?"
        description="Riwayat penugasan tetap tersimpan.">
        <div class="flex justify-end gap-2">
            <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
            <x-ui.button type="submit" form="close-homeroom-assignment">Konfirmasi</x-ui.button>
        </div>
    </x-ui.modal>
@endif
</x-ui.card>
@endsection
