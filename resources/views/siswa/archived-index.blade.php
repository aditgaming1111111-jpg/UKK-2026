@extends('layouts.admin')

@section('title', 'Arsip Siswa')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Data Siswa', 'url' => route('admin.students.index')],
        ['label' => 'Arsip Siswa'],
    ]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Arsip Siswa Keluar" description="Data siswa berstatus Keluar yang diarsipkan. Riwayat kelas dan pelanggaran tetap tersimpan.">
        <x-ui.button variant="secondary" :href="route('admin.students.index')">Kembali ke Data Siswa</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">{{ session('success') }}</div>
    @endif

    <x-ui.card>
        <form method="GET" action="{{ route('admin.students.archived.index') }}" class="flex flex-wrap items-end gap-3">
            <label class="min-w-56 flex-1 space-y-1.5">
                <span class="block text-sm font-medium text-gray-700">Cari siswa</span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, NIS, atau NISN"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
            </label>
            <x-ui.button type="submit">Cari</x-ui.button>
            <x-ui.button variant="secondary" :href="route('admin.students.archived.index')">Reset</x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.card :padding="false">
        @if ($students->isNotEmpty())
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Siswa</th>
                        <th class="px-5 py-3 font-medium">NIS</th>
                        <th class="px-5 py-3 font-medium">NISN</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($students as $student)
                        <tr>
                            <td class="px-5 py-4 text-sm font-semibold text-gray-900">{{ $student->nama }}</td>
                            <td class="px-5 py-4 text-sm text-gray-700">{{ $student->nis ?? '—' }}</td>
                            <td class="px-5 py-4 text-sm text-gray-700">{{ $student->nisn }}</td>
                            <td class="px-5 py-4"><x-ui.badge variant="outline">{{ ucfirst($student->status_siswa) }}</x-ui.badge></td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-3">
                                    <a class="text-sm font-medium text-gray-700 underline-offset-4 hover:underline" href="{{ route('admin.students.archived.show', $student->id) }}">Lihat riwayat</a>
                                    <button type="button" class="text-sm font-medium text-gray-700 underline-offset-4 hover:underline" data-modal-open="restore-student-{{ $student->id }}">Pulihkan</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="p-4"><x-ui.pagination :paginator="$students" /></div>
        @else
            <div class="p-5"><x-ui.empty-state title="Belum ada siswa diarsipkan" description="Siswa berstatus Keluar yang diarsipkan akan muncul di sini." /></div>
        @endif
    </x-ui.card>

    @foreach ($students as $student)
        <x-ui.modal :id="'restore-student-' . $student->id" title="Pulihkan siswa dari arsip?"
            :description="'Siswa ' . $student->nama . ' (NISN ' . $student->nisn . ') akan kembali ke daftar utama dengan status Keluar.'">
            <form method="POST" action="{{ route('admin.students.archived.restore', $student->id) }}">
                @csrf
                @method('PATCH')
                <div class="flex justify-end gap-2">
                    <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                    <x-ui.button type="submit">Pulihkan siswa</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endforeach
@endsection
