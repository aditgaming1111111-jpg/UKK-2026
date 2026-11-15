@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.guru')

{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Detail Siswa')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => $isArchived ? 'Arsip Siswa' : (auth()->user()->role === 'admin' ? 'Data Siswa' : 'Rekap Kelas Saya'), 'url' => $isArchived ? route('admin.students.archived.index') : route(auth()->user()->role === 'admin' ? 'admin.students.index' : 'guru.class-recap.index')],
        ['label' => $student->nama],
    ]" />
@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')
    <x-ui.page-heading :title="$student->nama" :description="$isArchived ? 'Arsip siswa Keluar beserta riwayat kelas dan pelanggaran.' : 'Identitas tetap, riwayat kelas, dan riwayat pelanggaran siswa.'">
        @if ($canManageStudents)
            <div class="flex flex-wrap gap-2"><x-ui.button variant="secondary" :href="route('admin.students.edit', $student)">Edit identitas</x-ui.button>
                @if ($student->status_siswa === 'aktif')
                    <x-ui.button variant="secondary" type="button"
                        data-modal-open="deactivate-student">Nonaktifkan</x-ui.button>
                    @if ($currentPlacement && $classes->count() > 1)
                        <x-ui.button type="button" data-modal-open="move-student-class">Pindah Kelas</x-ui.button>
                    @endif
                @elseif ($student->status_siswa === 'nonaktif')
                    <x-ui.button variant="secondary" type="button"
                        data-modal-open="activate-student">Aktifkan</x-ui.button>
                @endif
                @if ($student->status_siswa !== 'keluar' && $student->status_siswa !== 'lulus')
                    <x-ui.button variant="secondary" type="button" data-modal-open="exit-student">Tandai Keluar</x-ui.button>
                @endif
                @if ($canGraduate)
                    <x-ui.button variant="secondary" type="button" data-modal-open="graduate-student">Tandai lulus</x-ui.button>
                @endif
                @if ($student->status_siswa === 'keluar')
                    <x-ui.button variant="secondary" type="button" data-modal-open="archive-student">Arsipkan</x-ui.button>
                @endif
                @if ($canPermanentlyDelete)
                    <x-ui.button variant="secondary" type="button" data-modal-open="delete-student">Hapus permanen</x-ui.button>
                @endif
            </div>
        @elseif ($isArchived)
            <x-ui.button type="button" data-modal-open="restore-student">Pulihkan arsip</x-ui.button>
        @endif
    </x-ui.page-heading>
@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            {{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <x-ui.error-state title="Tindakan belum dilakukan" :description="$errors->first()" />
    @endif
    @if ($canManageStudents && !$canPermanentlyDelete)
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            Siswa berstatus Keluar/Lulus atau memiliki riwayat kelas/pelanggaran. Identitas tidak dapat dihapus permanen agar arsip dan histori tetap tersimpan.
        </div>
    @endif
    @if ($canManageStudents && $student->status_siswa === 'lulus')
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            Siswa ini tersimpan sebagai arsip alumni. Riwayat kelas, wali kelas, dan pelanggaran tetap dipertahankan.
        </div>
    @endif
    @if ($isArchived)
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            Siswa Keluar ini sedang diarsipkan. Identitas, penempatan kelas, dan riwayat pelanggaran tetap tersimpan.
        </div>
    @endif

    {{-- Bagian ini mengelompokkan informasi yang saling berkaitan. --}}
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
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">NIS</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $student->nis ?? 'Belum diisi' }}</dd>
                </div>
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
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tanggal lahir</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $student->tanggal_lahir?->format('d/m/Y') ?? 'Belum diisi' }}</dd>
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
            @if ($canManageStudents)
                <form id="student-status-form" method="POST" data-modal-confirm-form action="{{ route('admin.students.status', $student) }}" class="mt-5 flex flex-wrap items-end gap-3 border-t border-gray-100 pt-4">
                    @csrf
                    @method('PATCH')
                    <label class="min-w-56 flex-1 space-y-1.5">
                        <span class="block text-sm font-medium text-gray-700">Koreksi status siswa</span>
                        <select name="status_siswa" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                            @if ($student->status_siswa === 'pindah')
                                <option value="pindah" selected disabled>Pindah (status lama; pilih status pengganti)</option>
                            @endif
                            @foreach (['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'keluar' => 'Keluar'] as $statusValue => $statusLabel)
                                <option value="{{ $statusValue }}" @selected($student->status_siswa === $statusValue)>{{ $statusLabel }}</option>
                            @endforeach
                            @if ($canGraduate)
                                <option value="lulus">Lulus</option>
                            @elseif ($student->status_siswa === 'lulus')
                                <option value="lulus" selected>Lulus</option>
                            @endif
                        </select>
                    </label>
                    <x-ui.button type="button" data-modal-open="confirm-student-status">Simpan status</x-ui.button>
                </form>
            @endif
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

    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
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
                            @if ($canManageStudents)
                                <th class="px-4 py-3 text-right font-medium">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($placements as $placement)
                            @php
                                $hasViolationRecords = $placement->catatan_pelanggaran_count > 0;
                                $hasReplacementPlacement = $student->status_siswa !== 'aktif'
                                    || collect($activePlacementIds)->contains(fn ($activePlacementId) => $activePlacementId !== $placement->id);
                                $canDeletePlacement = $canManageStudents
                                    && !$hasViolationRecords
                                    && $hasReplacementPlacement;
                                $placementModalId = 'delete-placement-' . $placement->id;
                            @endphp
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
                                @if ($canManageStudents)
                                    <td class="px-4 py-3 text-right">
                                        <details class="mb-2 text-left">
                                            <summary class="cursor-pointer text-sm font-medium text-gray-700 underline decoration-gray-300 underline-offset-2">Koreksi tanggal</summary>
                                            {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
                                            <form method="POST" action="{{ route('admin.students.placements.update', [$student, $placement->id]) }}" class="mt-3 grid gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 sm:grid-cols-2">
                                                @csrf
                                                @method('PATCH')
                                                <label class="space-y-1.5">
                                                    <span class="block text-xs font-medium text-gray-700">Tanggal mulai</span>
                                                    <input type="date" name="tanggal_mulai" required value="{{ old('tanggal_mulai', $placement->tanggal_mulai?->toDateString()) }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                                    <x-ui.form-error name="tanggal_mulai" />
                                                </label>
                                                <label class="space-y-1.5">
                                                    <span class="block text-xs font-medium text-gray-700">Tanggal selesai (opsional)</span>
                                                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $placement->tanggal_selesai?->toDateString()) }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                                    <x-ui.form-error name="tanggal_selesai" />
                                                </label>
                                                <div class="flex justify-end sm:col-span-2">
                                                    <x-ui.button size="sm" type="submit">Simpan koreksi</x-ui.button>
                                                </div>
                                            </form>
                                        </details>
                                        @if ($canDeletePlacement)
                                            <x-ui.button variant="secondary" size="sm" type="button"
                                                :data-modal-open="$placementModalId">Hapus riwayat</x-ui.button>
                                        @elseif ($hasViolationRecords)
                                            <span class="text-xs text-gray-500">Terkait {{ $placement->catatan_pelanggaran_count }} catatan pelanggaran</span>
                                        @else
                                            <span class="text-xs text-gray-500">Siswa aktif perlu penempatan pengganti</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
            </x-ui.table></div>@else<x-ui.empty-state class="mt-5" title="Belum ada riwayat kelas"
                description="Admin dapat menambahkan penempatan kelas untuk tahun ajaran siswa." />
        @endif
        @if ($canManageStudents)
            @foreach ($placements as $placement)
                @php
                    $hasViolationRecords = $placement->catatan_pelanggaran_count > 0;
                    $hasReplacementPlacement = $student->status_siswa !== 'aktif'
                        || collect($activePlacementIds)->contains(fn ($activePlacementId) => $activePlacementId !== $placement->id);
                @endphp
                @if (!$hasViolationRecords && $hasReplacementPlacement)
                    {{-- Dialog ini memuat informasi atau konfirmasi sebelum tindakan dijalankan. --}}
                    <x-ui.modal :id="'delete-placement-' . $placement->id" title="Hapus riwayat penempatan?"
                        :description="'Siswa ' . $student->nama . ', kelas ' . ($placement->kelas?->nama_kelas ?? '—')
                            . ', tahun ajaran ' . ($placement->tahunAjaran?->nama_tahun_ajaran ?? '—')
                            . ', periode ' . ($placement->tanggal_mulai?->format('d/m/Y') ?? '—')
                            . ' sampai ' . ($placement->tanggal_selesai?->format('d/m/Y') ?? 'sekarang') . '.'">
                        <p class="mb-4 text-sm text-gray-600">Data penempatan ini akan dihapus permanen. Data siswa, penempatan lain, wali kelas, dan riwayat pelanggaran tidak ikut dihapus.</p>
                        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
                        <form method="POST" action="{{ route('admin.students.placements.destroy', [$student, $placement->id]) }}">
                            @csrf
                            @method('DELETE')
                            <div class="flex justify-end gap-2">
                                <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                                <x-ui.button type="submit">Hapus riwayat</x-ui.button>
                            </div>
                        </form>
                    </x-ui.modal>
                @endif
            @endforeach
        @endif
    </x-ui.card>

    @if ($canManageStudents && $student->status_siswa === 'aktif' && $activePlacementCount === 0)
        <x-ui.card>
            <h2 class="text-base font-semibold text-gray-950">Tambah penempatan kelas</h2>
            <p class="mt-1 text-sm text-gray-500">Gunakan untuk menambahkan penempatan setelah siswa diaktifkan kembali. Perpindahan dari kelas aktif dilakukan melalui tindakan Pindah Kelas.</p>
            @if ($classes->isNotEmpty() && $academicYears->isNotEmpty())
                {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
                <form id="student-placement-form" method="POST" data-modal-confirm-form action="{{ route('admin.students.placements.store', $student) }}"
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
                    <div class="flex items-end"><x-ui.button type="button" data-modal-open="confirm-student-placement">Simpan penempatan</x-ui.button></div>
            </form>@else<x-ui.empty-state class="mt-4" title="Data kelas atau tahun ajaran belum tersedia"
                    description="Tambahkan data kelas dan tahun ajaran terlebih dahulu." />
            @endif
        </x-ui.card>
    @endif

    @if ($canManageStudents && $student->status_siswa === 'aktif' && $activePlacementCount > 0 && !$currentPlacement)
        <x-ui.card>
            <x-ui.error-state title="Riwayat penempatan terbuka perlu diperiksa"
                description="Penempatan siswa tidak menunjukkan tepat satu penempatan aktif yang konsisten. Perbaiki riwayat penempatan sebelum memindahkan atau menambah penempatan baru." />
        </x-ui.card>
    @endif

    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-950">Riwayat pelanggaran</h2>
                <p class="mt-1 text-sm text-gray-500">Riwayat melekat pada kelas dan tahun ajaran saat kejadian.</p>
            </div><span class="text-xs text-gray-500">{{ $violations->total() }} catatan</span>
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
            </x-ui.table></div>
            <div class="mt-4"><x-ui.pagination :paginator="$violations" /></div>
        @else<x-ui.empty-state class="mt-5" title="Belum ada riwayat pelanggaran"
                description="Catatan pelanggaran siswa akan ditampilkan di sini." />
        @endif
    </x-ui.card>

    @if ($canManageStudents)
        <x-ui.modal id="confirm-student-status" title="Konfirmasi perubahan status"
            description="Status siswa {{ $student->nama }} akan diubah sesuai pilihan di atas.">
            <p class="mb-5 text-sm text-gray-600">Jika status menjadi Keluar atau Lulus, penempatan aktif akan diakhiri. Mengaktifkan kembali siswa yang Keluar atau Lulus tidak membuat penempatan baru. Riwayat kelas, wali kelas, dan pelanggaran tetap tersimpan.</p>
            <div class="flex justify-end gap-2">
                <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                <x-ui.button type="submit" form="student-status-form">Konfirmasi perubahan</x-ui.button>
            </div>
        </x-ui.modal>
    @endif

    @if ($canManageStudents && $student->status_siswa === 'aktif' && $classes->isNotEmpty() && $academicYears->isNotEmpty())
        <x-ui.modal id="confirm-student-placement" title="Konfirmasi perubahan kelas"
            description="Penempatan baru untuk {{ $student->nama }} akan menggunakan kelas dan tahun ajaran yang dipilih.">
            <p class="mb-5 text-sm text-gray-600">Penempatan baru akan ditambahkan tanpa mengubah riwayat penempatan atau catatan pelanggaran yang sudah tersimpan.</p>
            <div class="flex justify-end gap-2">
                <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                <x-ui.button type="submit" form="student-placement-form">Konfirmasi dan simpan</x-ui.button>
            </div>
        </x-ui.modal>
    @endif

    @if ($canManageStudents && $currentPlacement)
        <x-ui.modal id="move-student-class" title="Konfirmasi pindah kelas"
                :description="'Siswa ' . $student->nama . ' akan dipindahkan dari ' . ($currentPlacement->kelas?->nama_kelas ?? 'kelas tidak tersedia')
                    . ' pada tahun ajaran ' . ($currentPlacement->tahunAjaran?->nama_tahun_ajaran ?? '—')
                    . '. Penempatan lama berakhir sehari sebelum tanggal pindah, penempatan baru dimulai pada tanggal tersebut, status siswa tetap Aktif, dan semua histori tetap tersimpan.'">
                <form method="POST" action="{{ route('admin.students.move-class', $student) }}" class="grid gap-4">
                    @csrf
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700">
                        <p><span class="font-medium">Siswa:</span> {{ $student->nama }} (NISN {{ $student->nisn }})</p>
                        <p class="mt-1"><span class="font-medium">Kelas saat ini:</span> {{ $currentPlacement->kelas?->nama_kelas }} · {{ $currentPlacement->kelas?->tingkat }}{{ $currentPlacement->kelas?->jurusan ? ' · ' . $currentPlacement->kelas->jurusan : '' }}</p>
                        <p class="mt-1"><span class="font-medium">Tahun ajaran:</span> {{ $currentPlacement->tahunAjaran?->nama_tahun_ajaran }}</p>
                    </div>
                    <label class="space-y-1.5">
                        <span class="block text-sm font-medium text-gray-700">Kelas tujuan</span>
                        <select name="kelas_id" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                            <option value="">Pilih kelas tujuan</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @disabled($class->id === $currentPlacement->kelas_id)>
                                    {{ $class->nama_kelas }} · {{ $class->tingkat }}{{ $class->jurusan ? ' · ' . $class->jurusan : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="block text-xs text-gray-500">Jurusan mengikuti kelas tujuan yang dipilih.</span>
                        <x-ui.form-error name="kelas_id" />
                    </label>
                    <label class="space-y-1.5">
                        <span class="block text-sm font-medium text-gray-700">Tanggal pindah</span>
                        <input type="date" name="tanggal_pindah" required value="{{ old('tanggal_pindah') }}" min="{{ $currentPlacement->tanggal_mulai?->addDay()->toDateString() }}" max="{{ $currentPlacement->tahunAjaran?->tanggal_selesai?->toDateString() }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                        <span class="block text-xs text-gray-500">Tanggal harus berada setelah awal penempatan dan dalam tahun ajaran ini.</span>
                        <x-ui.form-error name="tanggal_pindah" />
                    </label>
                    <label class="space-y-1.5">
                        <span class="block text-sm font-medium text-gray-700">Alasan pindah</span>
                        <textarea name="alasan" required maxlength="2000" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200" placeholder="Jelaskan alasan perpindahan kelas">{{ old('alasan') }}</textarea>
                        <x-ui.form-error name="alasan" />
                    </label>
                    <div class="flex justify-end gap-2">
                        <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                        <x-ui.button type="submit">Konfirmasi dan pindahkan</x-ui.button>
                    </div>
                </form>
        </x-ui.modal>
    @endif

    @if ($canManageStudents && $student->status_siswa === 'aktif')
        {{-- Dialog ini memuat informasi atau konfirmasi sebelum tindakan dijalankan. --}}
        <x-ui.modal id="deactivate-student" title="Nonaktifkan siswa?"
            description="Identitas dan semua riwayat kelas serta pelanggaran tetap disimpan.">
            {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
            <form method="POST" action="{{ route('admin.students.status', $student) }}">@csrf @method('PATCH')
                <input type="hidden" name="status_siswa" value="nonaktif"><div
                    class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button"
                        data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Nonaktifkan siswa</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endif

    @if ($canManageStudents && $student->status_siswa !== 'keluar' && $student->status_siswa !== 'lulus')
        <x-ui.modal id="exit-student" title="Tandai siswa Keluar?"
            description="Siswa akan berstatus Keluar. Riwayat kelas dan pelanggaran tetap tersimpan, dan siswa tidak dapat dipilih untuk pencatatan baru.">
            <form method="POST" action="{{ route('admin.students.status', $student) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_siswa" value="keluar">
                <div class="flex justify-end gap-2">
                    <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                    <x-ui.button type="submit">Tandai Keluar</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endif

    @if ($canManageStudents && $student->status_siswa === 'keluar')
        <x-ui.modal id="archive-student" title="Arsipkan siswa Keluar?"
            :description="'Arsip siswa ' . $student->nama . ' (NISN ' . $student->nisn . '). Riwayat kelas dan pelanggaran tidak dihapus.'">
            <form method="POST" action="{{ route('admin.students.archive', $student) }}">
                @csrf
                @method('PATCH')
                <div class="flex justify-end gap-2">
                    <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                    <x-ui.button type="submit">Arsipkan siswa</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endif

    @if ($isArchived)
        <x-ui.modal id="restore-student" title="Pulihkan siswa dari arsip?"
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
    @endif

    @if ($canManageStudents && $canPermanentlyDelete)
        {{-- Dialog ini memuat informasi atau konfirmasi sebelum tindakan dijalankan. --}}
        <x-ui.modal id="delete-student" title="Hapus siswa permanen?"
            description="Siswa ini belum memiliki riwayat penempatan atau catatan pelanggaran. Data identitas akan dihapus permanen.">
            {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
            <form method="POST" action="{{ route('admin.students.destroy', $student) }}">@csrf @method('DELETE')
                <div class="flex justify-end gap-2">
                    <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                    <x-ui.button type="submit">Hapus permanen</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endif

    @if ($canManageStudents && $student->status_siswa === 'nonaktif')
        {{-- Dialog ini memuat informasi atau konfirmasi sebelum tindakan dijalankan. --}}
        <x-ui.modal id="activate-student" title="Aktifkan siswa?"
            description="Identitas dan seluruh riwayat kelas serta pelanggaran akan tetap tersimpan.">
            {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
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

    @if ($canManageStudents && $canGraduate)
        {{-- Dialog ini memuat informasi atau konfirmasi sebelum tindakan dijalankan. --}}
        <x-ui.modal id="graduate-student" title="Tandai siswa lulus?"
            description="Siswa akan masuk arsip alumni dan tidak tersedia untuk pencatatan pelanggaran baru. Riwayat kelas, wali kelas, dan pelanggaran tetap disimpan.">
            {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
            <form method="POST" action="{{ route('admin.students.status', $student) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_siswa" value="lulus">
                <div class="flex justify-end gap-2">
                    <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                    <x-ui.button type="submit">Tandai lulus</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endif
@endsection
