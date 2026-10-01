@extends('layouts.admin')

@section('title', 'Data Guru')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Data Guru']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Data Guru" description="Kelola identitas Guru dan akun login yang terhubung.">
        <x-ui.button :href="route('admin.teachers.create')">Tambah Guru</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            {{ session('success') }}</div>
    @endif
    <x-ui.card>
        <form method="GET" action="{{ route('admin.teachers.index') }}"
            class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_14rem_auto]">
            <x-ui.search placeholder="Cari NIP, nama, atau email" :value="$filters['q'] ?? null" />
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Status Guru</span><select
                    name="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif</option>
                </select></label>
            <div class="flex items-end gap-2"><x-ui.button type="submit">Terapkan</x-ui.button><x-ui.button
                    variant="secondary" :href="route('admin.teachers.index')">Reset</x-ui.button></div>
        </form>
    </x-ui.card>

    <x-ui.card :padding="false">
        @if ($teachers->isNotEmpty())
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Guru</th>
                        <th class="px-5 py-3 font-medium">NIP</th>
                        <th class="px-5 py-3 font-medium">Email Identitas</th>
                        <th class="px-5 py-3 font-medium">Akun Login</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($teachers as $teacher)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4"><a class="font-semibold text-gray-900 underline-offset-4 hover:underline"
                                    href="{{ route('admin.teachers.show', $teacher) }}">{{ $teacher->nama }}</a></td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700">{{ $teacher->nip }}</td>
                            <td class="px-5 py-4 text-sm text-gray-700">{{ $teacher->email }}</td>
                            <td class="px-5 py-4 text-sm text-gray-700">
                                @if ($teacher->user)
                                    <span>{{ $teacher->user->email }}</span>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $teacher->user->role === 'guru' ? 'Role Guru' : 'Relasi role tidak sesuai' }}</p>
                                @else<span class="text-gray-500">Belum dibuat</span>
                                @endif
                            </td>
                            <td class="px-5 py-4"><x-ui.badge
                                    :variant="$teacher->status_aktif ? 'dark' : 'outline'">{{ $teacher->status_aktif ? 'Aktif' : 'Nonaktif' }}</x-ui.badge></td>
                            <td class="whitespace-nowrap px-5 py-4 text-right"><a
                                    class="text-sm font-medium text-gray-700 underline-offset-4 hover:underline"
                                    href="{{ route('admin.teachers.show', $teacher) }}">Detail</a><span
                                    class="px-2 text-gray-300">·</span><a
                                    class="text-sm font-medium text-gray-700 underline-offset-4 hover:underline"
                                    href="{{ route('admin.teachers.edit', $teacher) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-5 py-4">{{ $teachers->links() }}</div>
        @else
            <div class="p-5"><x-ui.empty-state title="Data Guru belum tersedia"
                    description="Tambahkan identitas Guru untuk mulai mengelola akun dan penugasannya." />
                <div class="mt-4 text-center"><x-ui.button size="sm" :href="route('admin.teachers.create')">Tambah Guru</x-ui.button></div>
            </div>
        @endif
    </x-ui.card>
@endsection
