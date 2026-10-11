@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.guru')

@section('title', 'Detail Siswa')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Data Siswa', 'url' => route(auth()->user()->role . '.students.index')],
        ['label' => $student->nama],
    ]" />
@endsection

@section('page-heading')
    <x-ui.page-heading :title="$student->nama" description="Identitas tetap, riwayat kelas, dan riwayat pelanggaran siswa.">
        @if ($canManageStudents)
            <div class="flex flex-wrap gap-2"><x-ui.button variant="secondary" :href="route('admin.students.edit', $student)">Edit identitas</x-ui.button>
                @if ($student->status_siswa === 'aktif')
                    <x-ui.button variant="secondary" type="button"
                        data-modal-open="deactivate-student">Nonaktifkan</x-ui.button>
                @else
                    <x-ui.button variant="secondary" type="button"
                        data-modal-open="activate-student">Aktifkan</x-ui.button>
                @endif
            </div>
        @endif
    </x-ui.page-heading>
@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            {{ session('success') }}</div>
    @endif

    <section class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <x-ui.card>
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-gray-950">Identitas siswa</h2>
                    <p class="mt-1 text-sm text-gray-500">Data identitas tidak berubah saat siswa naik kelas.</p>
                </div><x-ui.badge :variant="$student->status_siswa === 'aktif' ? 'dark' : 'outline'">{{ ucfirst($student->status_siswa) }}</x-ui.badge>
            </div>
            <dl class="mt-5 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">NISN</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $student->nisn }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Jenis kelamin</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tahun masuk</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $student->tahun_masuk }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Kelas saat ini</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $placements->first(fn($placement) => $placement->status_aktif && $placement->tanggal_selesai === null)?->kelas?->nama_kelas ?? 'Belum ditempatkan' }}
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Alamat</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $student->alamat ?: 'Belum diisi' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card>
            <h2 class="text-base font-semibold text-gray-950">Rekap poin per tahun ajaran</h2>
            <p class="mt-1 text-sm text-gray-500">Hanya catatan aktif; memakai poin saat dicatat.</p>
            @if ($pointRecap->isNotEmpty())
                <ul class="mt-4 divide-y divide-gray-100">
                    @foreach ($pointRecap as $recap)
                            <li class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0"><span
                                    class="text-sm text-gray-700">{{ $recap['academic_year']->nama_tahun_ajaran }}</span><x-ui.badge>{{ number_format($recap['total_points'], 0, ',', '.') }} poin</x-ui.badge></li>
                    @endforeach
                </ul>
            @else<div class="mt-4"><x-ui.empty-state title="Belum ada rekap poin"
                        description="Poin aktif akan muncul setelah ada penempatan kelas dan catatan." /></div>
            @endif
        </x-ui.card>
    </section>

    <x-ui.card>
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-950">Riwayat kelas</h2>
                <p class="mt-1 text-sm text-gray-500">Penempatan lama tetap tersimpan sebagai histori.</p>
            </div><span class="text-xs text-gray-500">{{ $placements->count() }} penempatan</span>
        </div>
        @if ($placements->isNotEmpty())
            <div class="mt-5"><x-ui.table>
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Tahun ajaran</th>
                            <th class="px-4 py-3 font-medium">Kelas</th>
                            <th class="px-4 py-3 font-medium">Periode</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($placements as $placement)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $placement->tahunAjaran?->nama_tahun_ajaran ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $placement->kelas?->nama_kelas ?? '—' }}<p
                                        class="mt-1 text-xs text-gray-500">
                                        {{ $placement->kelas?->tingkat }}{{ $placement->kelas?->jurusan ? ' · ' . $placement->kelas->jurusan : '' }}
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                    {{ $placement->tanggal_mulai?->format('d/m/Y') }} –
                                    {{ $placement->tanggal_selesai?->format('d/m/Y') ?? 'Sekarang' }}</td>
                                <td class="px-4 py-3"><x-ui.badge
                                        :variant="$placement->status_aktif ? 'dark' : 'outline'">{{ $placement->status_aktif ? 'Aktif' : 'Selesai' }}</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </x-ui.table></div>@else<x-ui.empty-state class="mt-5" title="Belum ada riwayat kelas"
                description="Admin dapat menambahkan penempatan kelas untuk tahun ajaran siswa." />
        @endif
    </x-ui.card>

    @if ($canManageStudents && $student->status_siswa === 'aktif')
        <x-ui.card>
            <h2 class="text-base font-semibold text-gray-950">Tambah penempatan kelas</h2>
            <p class="mt-1 text-sm text-gray-500">Tanggal mulai harus berada di dalam periode tahun ajaran. Penempatan aktif
                sebelumnya akan diakhiri sehari sebelum tanggal ini.</p>
            @if ($classes->isNotEmpty() && $academicYears->isNotEmpty())
                <form method="POST" action="{{ route('admin.students.placements.store', $student) }}"
                    class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">@csrf
                    <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Kelas</span><select
                            name="kelas_id" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                            <option value="">Pilih kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('kelas_id') == $class->id)>{{ $class->nama_kelas }}
                                    · {{ $class->tingkat }}{{ $class->jurusan ? ' · ' . $class->jurusan : '' }}</option>
                            @endforeach
                        </select>
                        <x-ui.form-error name="kelas_id" /></label>
                    <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Tahun
                            ajaran</span><select name="tahun_ajaran_id" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                            <option value="">Pilih tahun ajaran</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected(old('tahun_ajaran_id') == $year->id)>
                                    {{ $year->nama_tahun_ajaran }}</option>
                            @endforeach
                        </select>
                        <x-ui.form-error name="tahun_ajaran_id" /></label>
                    <div><x-ui.input label="Tanggal mulai" name="tanggal_mulai" type="date" :value="now()->toDateString()" required />
                    </div>
                    <div class="flex items-end"><x-ui.button type="submit">Simpan penempatan</x-ui.button></div>
            </form>@else<x-ui.empty-state class="mt-4" title="Data kelas atau tahun ajaran belum tersedia"
                    description="Tambahkan data kelas dan tahun ajaran terlebih dahulu." />
            @endif
        </x-ui.card>
    @endif

    <x-ui.card>
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-950">Riwayat pelanggaran</h2>
                <p class="mt-1 text-sm text-gray-500">Riwayat melekat pada kelas dan tahun ajaran saat kejadian.</p>
            </div><span class="text-xs text-gray-500">{{ $violations->count() }} catatan</span>
        </div>
        @if ($violations->isNotEmpty())
            <div class="mt-5"><x-ui.table>
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Tanggal</th>
                            <th class="px-4 py-3 font-medium">Pelanggaran</th>
                            <th class="px-4 py-3 font-medium">Kelas / Tahun ajaran</th>
                            <th class="px-4 py-3 font-medium">Poin snapshot</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($violations as $record)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">
                                    {{ $record->tanggal_pelanggaran?->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $record->pelanggaran?->nama_pelanggaran ?? 'Jenis tidak tersedia' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $record->penempatanKelas?->kelas?->nama_kelas ?? '—' }}<p
                                        class="mt-1 text-xs text-gray-500">
                                        {{ $record->penempatanKelas?->tahunAjaran?->nama_tahun_ajaran ?? '—' }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                    {{ number_format($record->poin_saat_dicatat, 0, ',', '.') }}</td>
                                <td class="px-4 py-3"><x-ui.badge
                                        :variant="$record->status === 'aktif' ? 'dark' : 'outline'">{{ $record->status === 'dibatalkan' ? 'Dibatalkan' : ucfirst($record->status) }}</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </x-ui.table></div>@else<x-ui.empty-state class="mt-5" title="Belum ada riwayat pelanggaran"
                description="Catatan pelanggaran siswa akan ditampilkan di sini." />
        @endif
    </x-ui.card>

    @if ($canManageStudents && $student->status_siswa === 'aktif')
        <x-ui.modal id="deactivate-student" title="Nonaktifkan siswa?"
            description="Identitas dan semua riwayat kelas serta pelanggaran tetap disimpan.">
            <form method="POST" action="{{ route('admin.students.destroy', $student) }}">@csrf @method('DELETE')<div
                    class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button"
                        data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Nonaktifkan siswa</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endif

    @if ($canManageStudents && $student->status_siswa === 'nonaktif')
        <x-ui.modal id="activate-student" title="Aktifkan siswa?"
            description="Identitas dan seluruh riwayat kelas serta pelanggaran akan tetap tersimpan.">
            <form method="POST" action="{{ route('admin.students.status', $student) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_siswa" value="aktif">
                <div class="flex justify-end gap-2">
                    <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                    <x-ui.button type="submit">Aktifkan siswa</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endif
@endsection
