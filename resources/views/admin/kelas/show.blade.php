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
    <x-ui.card :padding="false">
        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-200 px-5 py-4">
            <div>
                <h2 class="font-semibold text-gray-900">Siswa saat ini</h2>
                <p class="mt-1 text-sm text-gray-500">Penempatan aktif pada tahun ajaran {{ $academicYear?->nama_tahun_ajaran ?? 'yang aktif' }}.</p>
            </div>
            @if ($academicYear)
                <form method="GET" action="{{ route('admin.classes.show', $kelas) }}" class="flex w-full items-end gap-2 sm:w-auto">
                    <x-ui.search name="q" placeholder="Cari nama atau NISN" :value="$filters['q'] ?? null" />
                    <x-ui.button type="submit">Cari</x-ui.button>
                </form>
            @endif
        </div>
        @if (!$academicYear)
            <div class="p-5"><x-ui.empty-state title="Belum ada tahun ajaran aktif" description="Daftar siswa saat ini tersedia setelah Admin menetapkan tahun ajaran aktif." /></div>
        @elseif ($placements->isNotEmpty())
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr><th class="px-5 py-3 font-medium">NISN</th><th class="px-5 py-3 font-medium">Nama siswa</th><th class="px-5 py-3 font-medium">Status penempatan</th><th class="px-5 py-3 text-right font-medium">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($placements as $placement)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm">{{ $placement->siswa->nisn }}</td>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $placement->siswa->nama }}</td>
                            <td class="px-5 py-4"><x-ui.badge variant="dark">Aktif</x-ui.badge></td>
                            <td class="px-5 py-4 text-right"><a class="text-sm font-medium underline" href="{{ route('admin.students.show', $placement->siswa) }}">Lihat detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 px-5 py-4">
                <p class="text-sm text-gray-500">Menampilkan {{ $placements->firstItem() }}–{{ $placements->lastItem() }} dari {{ $placements->total() }} siswa.</p>
                <x-ui.pagination :paginator="$placements" />
            </div>
        @else
            <div class="p-5"><x-ui.empty-state title="Belum ada siswa di kelas ini" description="Tidak ada siswa aktif dengan penempatan yang berlaku pada tahun ajaran ini untuk pencarian tersebut." /></div>
        @endif
    </x-ui.card>
    @if ($kelas->penempatan_siswa_count === 0 && $kelas->penugasan_wali_kelas_count === 0)
        <x-ui.modal id="delete-class" title="Hapus kelas?" description="Kelas belum direferensikan oleh histori mana pun.">
            <form method="POST" action="{{ route('admin.classes.destroy', $kelas) }}">@csrf @method('DELETE')<div
                    class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button"
                        data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Hapus kelas</x-ui.button></div>
            </form>
        </x-ui.modal>
    @endif
@endsection
