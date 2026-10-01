@extends('layouts.admin')
@section('title', 'Detail Kelas')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Data Kelas', 'url' => route('admin.classes.index')], ['label' => $kelas->nama_kelas]]" />@endsection
@section('page-heading')<x-ui.page-heading :title="$kelas->nama_kelas"
        description="Detail kelas dan jumlah riwayat yang terhubung.">
        <div class="flex gap-2"><x-ui.button variant="secondary" :href="route('admin.classes.edit', $kelas)">Edit</x-ui.button>
            @if ($kelas->penempatan_siswa_count === 0 && $kelas->penugasan_wali_kelas_count === 0)
                <x-ui.button variant="secondary" type="button" data-modal-open="delete-class">Hapus kelas</x-ui.button>
            @endif
        </div>
</x-ui.page-heading>@endsection
@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            {{ session('success') }}</div>
    @endif
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"><x-ui.stat-card label="Tingkat" :value="$kelas->tingkat"
            note="Tingkat kelas" icon="grid" /><x-ui.stat-card label="Jurusan" :value="$kelas->jurusan ?: '—'"
            note="Sesuai data master" icon="document" /><x-ui.stat-card label="Penempatan aktif" :value="$kelas->active_placement_count"
            note="Siswa saat ini" icon="users" /><x-ui.stat-card label="Riwayat terkait" :value="$kelas->penempatan_siswa_count + $kelas->penugasan_wali_kelas_count"
            note="Penempatan + wali kelas" icon="clock" /></section>
    @if ($kelas->penempatan_siswa_count > 0 || $kelas->penugasan_wali_kelas_count > 0)
        <x-ui.card>
            <p class="text-sm text-gray-700">Kelas memiliki riwayat penempatan atau penugasan. Kelas tidak dapat dihapus
                permanen; ubah nama atau jurusannya jika diperlukan.</p>
        </x-ui.card>
    @endif
    @if ($kelas->penempatan_siswa_count === 0 && $kelas->penugasan_wali_kelas_count === 0)
        <x-ui.modal id="delete-class" title="Hapus kelas?" description="Kelas belum direferensikan oleh histori mana pun.">
            <form method="POST" action="{{ route('admin.classes.destroy', $kelas) }}">@csrf @method('DELETE')<div
                    class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button"
                        data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Hapus kelas</x-ui.button></div>
            </form>
        </x-ui.modal>
    @endif
@endsection
