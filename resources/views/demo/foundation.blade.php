@extends($layout)

@section('title', 'Demo Komponen')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Beranda', 'url' => '/'], ['label' => 'Fondasi UI']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Fondasi antarmuka" description="Contoh komponen dasar SekolahKu dalam tampilan monochrome.">
        <x-ui.button data-toast-trigger="app-toast"><x-ui.icon name="check" class="size-4" /> Simpan contoh</x-ui.button>
    </x-ui.page-heading>
@endsection

@section('content')
    <section aria-label="Ringkasan" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.stat-card label="Siswa terdata" value="1.248" note="Data contoh" icon="users" />
        <x-ui.stat-card label="Pelanggaran aktif" value="36" note="Tahun ajaran berjalan" icon="warning" />
        <x-ui.stat-card label="Poin tercatat" value="214" note="Akumulasi contoh" icon="chart" />
        <x-ui.stat-card label="Menunggu tindak lanjut" value="08" note="Perlu ditinjau" icon="clock" />
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(18rem,1fr)]">
        <x-ui.card>
            <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold">Daftar catatan terbaru</h2>
                    <p class="mt-1 text-sm text-gray-500">Contoh tabel, pencarian, filter, dan status.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row"><x-ui.search placeholder="Cari nama siswa"
                        class="sm:w-56" /><x-ui.filter label="Kelas"><select
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <option>Semua kelas</option>
                            <option>VII A</option>
                        </select></x-ui.filter></div>
            </div>
            <div class="mt-5 space-y-4">
                <x-ui.table>
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Siswa</th>
                            <th class="px-4 py-3 font-medium">Kejadian</th>
                            <th class="px-4 py-3 font-medium">Poin</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ([['Raka Pratama', 'VII A', '12 Sep 2026', '5', 'Aktif'], ['Nadia Putri', 'VIII B', '11 Sep 2026', '3', 'Ditinjau'], ['Dimas Saputra', 'IX A', '10 Sep 2026', '8', 'Dibatalkan']] as [$student, $class, $date, $points, $status])
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-4 py-3">
                                    <p class="font-medium text-gray-900">{{ $student }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $class }}</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600">{{ $date }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $points }} poin</td>
                                <td class="px-4 py-3"><x-ui.badge :variant="$status === 'Aktif' ? 'dark' : 'default'">{{ $status }}</x-ui.badge></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-ui.table>
                <x-ui.pagination :current="1" :last="8" />
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card>
                <h2 class="text-base font-semibold">Elemen form</h2>
                <p class="mt-1 text-sm text-gray-500">Input, pilihan, catatan, dan validasi.</p>
                <div class="mt-5 space-y-4"><x-ui.input label="Nama siswa" name="nama_demo" placeholder="Masukkan nama"
                        hint="Contoh input dengan teks bantuan." /><x-ui.select label="Kelas" name="kelas_demo"
                        :options="['VII-A' => 'VII A', 'VIII-B' => 'VIII B']" /><x-ui.textarea label="Keterangan" name="keterangan_demo" rows="3"
                        placeholder="Tambahkan catatan…" />
                    <p class="text-xs font-medium text-gray-600">Feedback validasi: nama siswa wajib diisi.</p>
                    <div class="flex flex-wrap gap-2"><x-ui.button>Simpan</x-ui.button><x-ui.button
                            variant="secondary">Batal</x-ui.button></div>
                </div>
            </x-ui.card>
            <x-ui.error-state title="Contoh error" description="Data gagal dimuat. Periksa koneksi lalu coba kembali.">
                <div class="mt-3"><x-ui.button variant="secondary" size="sm">Coba lagi</x-ui.button></div>
            </x-ui.error-state>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.card>
            <h2 class="mb-4 text-base font-semibold">Keadaan kosong</h2><x-ui.empty-state title="Belum ada catatan"
                description="Catatan baru akan muncul di bagian ini." />
        </x-ui.card>
        <x-ui.card>
            <h2 class="mb-4 text-base font-semibold">Skeleton pemuatan</h2><x-ui.skeleton :lines="3" />
        </x-ui.card>
        <x-ui.card>
            <h2 class="text-base font-semibold">Konfirmasi tindakan</h2>
            <p class="mt-1 text-sm text-gray-500">Modal reusable dengan dialog native dan tombol tutup.</p>
            <div class="mt-5"><x-ui.button variant="secondary" data-modal-open="demo-confirmation">Buka
                    konfirmasi</x-ui.button></div>
        </x-ui.card>
    </div>

    <x-ui.modal id="demo-confirmation" title="Batalkan catatan?"
        description="Tindakan ini akan mengubah status catatan contoh.">
        <p class="text-sm text-gray-600">Catatan tetap disimpan untuk kebutuhan riwayat dan audit.</p>
        <div class="mt-6 flex justify-end gap-2"><x-ui.button variant="secondary"
                data-modal-close>Tutup</x-ui.button><x-ui.button data-modal-close>Ya, lanjutkan</x-ui.button></div>
    </x-ui.modal>
    <x-ui.toast id="app-toast" message="Contoh interaksi berhasil dijalankan." />
@endsection
