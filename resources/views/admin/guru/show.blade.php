@extends('layouts.admin')

@section('title', 'Detail Guru')

@section('breadcrumbs')<x-ui.breadcrumbs :items="[['label' => 'Data Guru', 'url' => route('admin.teachers.index')], ['label' => $teacher->nama]]" />@endsection

@section('page-heading')
    <x-ui.page-heading :title="$teacher->nama" description="Identitas Guru dan akun pengguna yang terhubung.">
        <div class="flex flex-wrap gap-2"><x-ui.button variant="secondary" :href="route('admin.teachers.edit', $teacher)">Edit identitas</x-ui.button>
            @if ($teacher->status_aktif)
                <x-ui.button variant="secondary" type="button"
                data-modal-open="deactivate-guru">Nonaktifkan</x-ui.button>@else<x-ui.button variant="secondary"
                    type="button" data-modal-open="activate-guru">Aktifkan kembali</x-ui.button>
            @endif
        </div>
    </x-ui.page-heading>
@endsection

@section('content')
    @if (session('success'))
        <div role="status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700">
            {{ session('success') }}</div>
    @endif
    @if ($errors->has('akun'))
        <x-ui.error-state title="Relasi akun perlu diperiksa" :description="$errors->first('akun')" />
    @endif

    <section class="grid gap-5 xl:grid-cols-2">
        <x-ui.card>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-950">Identitas Guru</h2>
                    <p class="mt-1 text-sm text-gray-500">Data identitas tersimpan pada tabel Guru.</p>
                </div><x-ui.badge :variant="$teacher->status_aktif ? 'dark' : 'outline'">{{ $teacher->status_aktif ? 'Aktif' : 'Nonaktif' }}</x-ui.badge>
            </div>
            <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">NIP</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $teacher->nip }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Nama</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $teacher->nama }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $teacher->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Dibuat</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $teacher->created_at?->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card>
            <div>
                <h2 class="text-base font-semibold text-gray-950">Akun login</h2>
                <p class="mt-1 text-sm text-gray-500">Akun memiliki role Guru dan relasi satu-ke-satu dengan identitas ini.
                </p>
            </div>
            @if (!$teacher->user)
                <x-ui.error-state class="mt-4" title="Akun login tidak ditemukan"
                    description="Relasi akun Guru tidak valid. Periksa konsistensi data sebelum melakukan perubahan." />
            @elseif($teacher->user->role !== 'guru')
                <div role="alert" class="mt-4 rounded-lg border border-gray-300 bg-gray-50 p-4 text-sm text-gray-700">Akun
                    terhubung memiliki role <strong>{{ $teacher->user->role }}</strong>, bukan Guru. Pengaturan akun dikunci
                    agar hak akses Admin tidak berubah.</div>
            @else
                <form method="POST" action="{{ route('admin.teachers.account.update', $teacher) }}" class="mt-5 space-y-4">
                    @csrf @method('PUT')
                    <p class="text-sm text-gray-800">Email login: <span class="font-medium">{{ $teacher->email }}</span></p>
                    @if ($teacher->user->email !== $teacher->email)
                        <p role="alert" class="rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-700">Email akun lama berbeda. Simpan perubahan email pada halaman Edit Identitas untuk menyamakan email identitas dan login.</p>
                    @endif
                    <p class="text-xs text-gray-500">Email login mengikuti email identitas. Kosongkan kata sandi jika tidak ingin menggantinya. Status login mengikuti status Guru.</p>
                    <x-ui.input label="Kata sandi baru (opsional)" name="password_login" type="password"
                        autocomplete="new-password" hint="Minimal 8 karakter." />
                    <x-ui.input label="Konfirmasi kata sandi" name="password_login_confirmation" type="password"
                        autocomplete="new-password" />
                    @if ($teacher->user)
                        <p class="text-xs text-gray-500">Role: {{ $teacher->user->role }} · {{ $teacher->status_aktif ? 'Aktif' : 'Nonaktif' }}</p>
                    @endif
                    <div class="flex justify-end"><x-ui.button type="submit">Simpan akun</x-ui.button></div>
                </form>
            @endif
        </x-ui.card>
    </section>

    <x-ui.card>
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-950">Riwayat penugasan wali kelas</h2>
                <p class="mt-1 text-sm text-gray-500">Histori penugasan tetap terhubung dengan identitas Guru.</p>
            </div><span class="text-xs text-gray-500">{{ $teacher->waliKelas->count() }} penugasan</span>
        </div>
        @if ($teacher->waliKelas->isNotEmpty())
            <div class="mt-5"><x-ui.table>
                    <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Tahun ajaran</th>
                            <th class="px-4 py-3 font-medium">Kelas</th>
                            <th class="px-4 py-3 font-medium">Periode</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($teacher->waliKelas as $assignment)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    {{ $assignment->tahunAjaran?->nama_tahun_ajaran ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $assignment->kelas?->nama_kelas ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                    {{ $assignment->tanggal_mulai?->format('d/m/Y') }} –
                                    {{ $assignment->tanggal_selesai?->format('d/m/Y') ?? 'Sekarang' }}</td>
                                <td class="px-4 py-3"><x-ui.badge
                                        :variant="$assignment->status_aktif ? 'dark' : 'outline'">{{ $assignment->status_aktif ? 'Aktif' : 'Selesai' }}</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </x-ui.table></div>@else<x-ui.empty-state class="mt-5" title="Belum ada penugasan wali kelas"
                description="Penugasan kelas akan muncul di sini setelah dikelola." />
        @endif
    </x-ui.card>

    @if ($teacher->status_aktif)
        <x-ui.modal id="deactivate-guru" title="Nonaktifkan Guru?"
            description="Akun login tidak akan dapat digunakan selama Guru nonaktif. Relasi akun, penugasan, dan catatan pelanggaran tetap disimpan.">
            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}">@csrf @method('DELETE')<div
                    class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button"
                        data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Nonaktifkan Guru</x-ui.button></div>
            </form>
        </x-ui.modal>
    @else
        <x-ui.modal id="activate-guru" title="Aktifkan Guru kembali?"
            description="Akun login yang terhubung akan dapat digunakan kembali.">
            <form method="POST" action="{{ route('admin.teachers.activate', $teacher) }}">@csrf @method('PATCH')<div
                    class="flex justify-end gap-2"><x-ui.button variant="secondary" type="button"
                        data-modal-close>Batal</x-ui.button><x-ui.button type="submit">Aktifkan Guru</x-ui.button></div>
            </form>
        </x-ui.modal>
    @endif
@endsection
