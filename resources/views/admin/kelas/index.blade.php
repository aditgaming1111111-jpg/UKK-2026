@extends('layouts.admin')

@section('title', 'Data Kelas')
@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Data Kelas']]" />
@endsection
@section('page-heading')<x-ui.page-heading title="Data Kelas"
        description="Kelola tingkat dan jurusan kelas. Status pemakaian diturunkan dari penempatan siswa aktif."><x-ui.button
        :href="route('admin.classes.create')">Tambah kelas</x-ui.button></x-ui.page-heading>@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            {{ session('success') }}</div>
    @endif
    @if ($errors->has('kelas'))
        <x-ui.error-state title="Kelas tidak dapat dihapus" :description="$errors->first('kelas')" />
    @endif
    <x-ui.card>
        <form method="GET" action="{{ route('admin.classes.index') }}"
            class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_14rem_auto]"><x-ui.search
                placeholder="Cari nama kelas, tingkat, jurusan" :value="$filters['q'] ?? null" /><label class="space-y-1.5"><span
                    class="block text-sm font-medium text-gray-700">Pemakaian saat ini</span><select name="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua kelas</option>
                    <option value="terisi" @selected(($filters['status'] ?? '') === 'terisi')>Ada penempatan aktif</option>
                    <option value="kosong" @selected(($filters['status'] ?? '') === 'kosong')>Belum ada penempatan aktif</option>
                </select></label>
            <div class="flex items-end gap-2"><x-ui.button type="submit">Terapkan</x-ui.button><x-ui.button
                    variant="secondary" :href="route('admin.classes.index')">Reset</x-ui.button></div>
        </form>
    </x-ui.card>
    <x-ui.card :padding="false">
        @if ($classes->isNotEmpty())<x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Nama kelas</th>
                        <th class="px-5 py-3 font-medium">Tingkat</th>
                        <th class="px-5 py-3 font-medium">Jurusan</th>
                        <th class="px-5 py-3 font-medium">Pemakaian</th>
                        <th class="px-5 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($classes as $kelas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4"><a class="font-semibold text-gray-900 hover:underline"
                                    href="{{ route('admin.classes.show', $kelas) }}">{{ $kelas->nama_kelas }}</a></td>
                            <td class="px-5 py-4 text-sm text-gray-700">{{ $kelas->tingkat }}</td>
                            <td class="px-5 py-4 text-sm text-gray-700">{{ $kelas->jurusan ?: '—' }}</td>
                            <td class="px-5 py-4"><x-ui.badge
                                    :variant="$kelas->active_placement_count > 0 ? 'dark' : 'outline'">{{ $kelas->active_placement_count > 0 ? 'Ada siswa aktif' : 'Belum terisi' }}</x-ui.badge>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right"><a
                                    class="text-sm font-medium text-gray-700 hover:underline"
                                    href="{{ route('admin.classes.show', $kelas) }}">Detail</a><span
                                    class="px-2 text-gray-300">·</span><a
                                    class="text-sm font-medium text-gray-700 hover:underline"
                                    href="{{ route('admin.classes.edit', $kelas) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-5 py-4">{{ $classes->links() }}</div>
        @else<div class="p-5"><x-ui.empty-state title="Data kelas belum tersedia"
                    description="Ubah filter atau tambahkan data kelas." />
                <div class="mt-4 text-center"><x-ui.button size="sm" :href="route('admin.classes.create')">Tambah kelas</x-ui.button></div>
            </div>
        @endif
    </x-ui.card>
@endsection
