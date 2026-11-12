@extends('layouts.guru')

{{-- Menetapkan judul halaman yang dipakai oleh kerangka aplikasi. --}}
@section('title', 'Rekap Poin')

{{-- Menunjukkan posisi halaman dan jalur kembali ke bagian induk. --}}
@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
        ['label' => 'Dashboard', 'url' => route('guru.dashboard')],
        ['label' => 'Rekap Poin'],
    ]" />
@endsection

{{-- Menyajikan judul, ringkasan, serta aksi utama untuk halaman ini. --}}
@section('page-heading')
    <x-ui.page-heading title="Rekap Poin" description="Total poin aktif siswa berdasarkan tahun ajaran. Catatan yang dibatalkan tidak dihitung." />
@endsection

{{-- Memuat konten utama halaman yang ditampilkan di dalam kerangka aplikasi. --}}
@section('content')
    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card>
        {{-- Formulir ini mengirim pilihan atau data untuk tindakan pada halaman. --}}
        <form method="GET" action="{{ route('guru.point-recap.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-ui.search placeholder="Cari nama atau NISN" :value="$filters['q'] ?? null" />
            <label class="space-y-1.5">
                <span class="block text-sm font-medium text-gray-700">Tahun ajaran</span>
                <select name="tahun_ajaran_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    @forelse ($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}" @selected(($filters['tahun_ajaran_id'] ?? '') == $academicYear->id)>
                            {{ $academicYear->nama_tahun_ajaran }}{{ $academicYear->status_aktif ? ' · Aktif' : '' }}
                        </option>
                    @empty
                        <option value="">Belum ada tahun ajaran</option>
                    @endforelse
                </select>
            </label>
            <div class="flex items-end gap-2">
                <x-ui.button type="submit">Terapkan</x-ui.button>
                <x-ui.button variant="secondary" :href="route('guru.point-recap.index')">Reset</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    {{-- Kartu ini membatasi satu kelompok informasi atau kontrol halaman. --}}
    <x-ui.card :padding="false">
        @if ($students->isNotEmpty())
            {{-- Tabel berikut merangkum data dalam susunan kolom yang mudah dipindai. --}}
            <x-ui.table>
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Siswa</th>
                        <th class="px-5 py-3 font-medium">NISN</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 text-right font-medium">Poin aktif{{ $selectedAcademicYear ? ' · '.$selectedAcademicYear->nama_tahun_ajaran : '' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 text-sm font-semibold text-gray-900">{{ $student->nama }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700">{{ $student->nisn }}</td>
                            <td class="px-5 py-4 text-sm text-gray-700">{{ ucfirst($student->status_siswa) }}</td>
                            <td class="px-5 py-4 text-right text-sm font-semibold text-gray-900">{{ number_format($student->active_points, 0, ',', '.') }} poin</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="border-t border-gray-100 px-5 py-4">
                <x-ui.pagination :paginator="$students" />
            </div>
        @else
            <x-ui.empty-state title="Siswa tidak ditemukan" description="Ubah kata kunci pencarian atau periksa kembali data siswa." />
        @endif
    </x-ui.card>
@endsection
