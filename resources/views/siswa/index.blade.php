@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.guru')

@section('title', 'Data Siswa')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Data Siswa']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Data Siswa" description="Cari dan lihat identitas serta kelas siswa.">
        @if ($canManageStudents)
            <x-ui.button :href="route('admin.students.create')">Tambah siswa</x-ui.button>
        @endif
    </x-ui.page-heading>
@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            {{ session('success') }}</div>
    @endif

    <x-ui.card>
        <form method="GET" action="{{ route(auth()->user()->role . '.students.index') }}"
            class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
            <div class="sm:col-span-2"><x-ui.search placeholder="Cari NISN atau nama siswa" :value="$filters['q'] ?? null" /></div>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Kelas</span><select
                    name="kelas_id"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(($filters['kelas_id'] ?? '') == $class->id)>{{ $class->nama_kelas }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Tingkat</span><select
                    name="tingkat"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua tingkat</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level }}" @selected(($filters['tingkat'] ?? '') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Jurusan</span><select
                    name="jurusan"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua jurusan</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major }}" @selected(($filters['jurusan'] ?? '') === $major)>{{ $major }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Status</span><select
                    name="status"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif</option>
                </select></label>
            <div class="flex items-end gap-2"><x-ui.button type="submit">Terapkan</x-ui.button><x-ui.button
                    variant="secondary" :href="route(auth()->user()->role . '.students.index')">Reset</x-ui.button></div>
        </form>
    </x-ui.card>

    <x-ui.card :padding="false">
        @if ($students->isNotEmpty())
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Siswa</th>
                        <th class="px-5 py-3 font-medium">NISN</th>
                        <th class="px-5 py-3 font-medium">Kelas saat ini</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($students as $student)
                        @php($currentPlacement = $student->penempatanKelas->first())
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4"><a class="font-semibold text-gray-900 underline-offset-4 hover:underline"
                                    href="{{ route(auth()->user()->role . '.students.show', $student) }}">{{ $student->nama }}</a>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700">{{ $student->nisn }}</td>
                            <td class="px-5 py-4 text-sm text-gray-700">
                                {{ $currentPlacement?->kelas?->nama_kelas ?? 'Belum ditempatkan' }}@if ($currentPlacement?->kelas)
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $currentPlacement->kelas->tingkat }}{{ $currentPlacement->kelas->jurusan ? ' · ' . $currentPlacement->kelas->jurusan : '' }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-5 py-4"><x-ui.badge
                                    :variant="$student->status_siswa === 'aktif' ? 'dark' : 'outline'">{{ ucfirst($student->status_siswa) }}</x-ui.badge></td>
                            <td class="whitespace-nowrap px-5 py-4 text-right"><a
                                    class="text-sm font-medium text-gray-700 underline-offset-4 hover:underline"
                                    href="{{ route(auth()->user()->role . '.students.show', $student) }}">Lihat detail</a>
                                @if ($canManageStudents)
                                    <span class="px-2 text-gray-300">·</span><a
                                        class="text-sm font-medium text-gray-700 underline-offset-4 hover:underline"
                                        href="{{ route('admin.students.edit', $student) }}">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-200 px-5 py-4">{{ $students->links() }}</div>
        @else
            <div class="p-5"><x-ui.empty-state title="Siswa tidak ditemukan"
                    description="Ubah kata pencarian atau filter untuk melihat hasil lain." />
                @if ($canManageStudents)
                    <div class="mt-4 text-center"><x-ui.button size="sm" :href="route('admin.students.create')">Tambah siswa</x-ui.button>
                    </div>
                @endif
            </div>
        @endif
    </x-ui.card>
@endsection
