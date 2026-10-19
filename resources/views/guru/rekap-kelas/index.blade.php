@extends('layouts.guru')

@section('title', 'Rekap Kelas Saya')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Rekap Kelas Saya']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Rekap Kelas Saya" description="Rekap berdasarkan kelas dan periode penugasan wali kelas Anda." />
@endsection

@section('content')
    <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-100 pb-4">
            <div>
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <h2 class="font-semibold text-gray-950">{{ $selectedClass->nama_kelas }}</h2>
                    <span class="text-gray-400" aria-hidden="true">/</span>
                    <span class="text-sm font-medium text-gray-700">{{ $selectedYear->nama_tahun_ajaran }}</span>
                </div>
                <p class="mt-1 text-sm text-gray-600">Wali kelas: {{ $teacher->nama }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @php($exportParameters = ['tahunAjaran' => $selectedYear->id, 'kelas' => $selectedClass->id] + array_filter(request()->only('q', 'tanggal_mulai', 'tanggal_selesai'), static fn ($value): bool => $value !== null && $value !== ''))
                <a href="{{ route('guru.class-recap.pdf', $exportParameters) }}" class="inline-flex min-h-10 items-center justify-center rounded-lg border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">Unduh PDF</a>
                <a href="{{ route('guru.class-recap.excel', $exportParameters) }}" class="inline-flex min-h-10 items-center justify-center rounded-lg border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">Unduh Excel</a>
            </div>
        </div>
        <form method="GET" action="{{ route('guru.class-recap.index') }}" class="mt-5 space-y-4">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <label for="tahun_ajaran_id" class="block space-y-1.5 text-sm font-medium text-gray-800">
                    <span>Tahun ajaran</span>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" onchange="this.form.elements.kelas_id.value = ''" class="block min-h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                        @foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) ($filters['tahun_ajaran_id'] ?? $selectedYear->id) === $year->id)>{{ $year->nama_tahun_ajaran }}</option>@endforeach
                    </select>
                </label>
                <label for="kelas_id" class="block space-y-1.5 text-sm font-medium text-gray-800">
                    <span>Kelas</span>
                    <select id="kelas_id" name="kelas_id" class="block min-h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                        <option value="">Kelas pertama yang ditugaskan</option>
                        @foreach ($classes as $class)<option value="{{ $class->id }}" @selected((int) ($filters['kelas_id'] ?? $selectedClass->id) === $class->id)>{{ $class->nama_kelas }}</option>@endforeach
                    </select>
                </label>
                <label for="q" class="block space-y-1.5 text-sm font-medium text-gray-800">
                    <span>Cari siswa</span>
                    <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="block min-h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-gray-500 focus:ring-2 focus:ring-gray-200" placeholder="Nama atau NISN">
                </label>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <label for="tanggal_mulai" class="block space-y-1.5 text-sm font-medium text-gray-800">
                    <span>Kejadian mulai</span>
                    <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="block min-h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    @error('tanggal_mulai')<span class="block text-xs font-normal text-gray-700">{{ $message }}</span>@enderror
                </label>
                <label for="tanggal_selesai" class="block space-y-1.5 text-sm font-medium text-gray-800">
                    <span>Kejadian sampai</span>
                    <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="block min-h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
                    @error('tanggal_selesai')<span class="block text-xs font-normal text-gray-700">{{ $message }}</span>@enderror
                </label>
                <div class="flex flex-wrap items-end gap-2">
                    <button class="inline-flex min-h-11 items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">Terapkan filter</button>
                    <a href="{{ route('guru.class-recap.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">Reset</a>
                </div>
            </div>
        </form>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.stat-card label="Jumlah siswa" :value="$summary['student_count']" />
        <x-ui.stat-card label="Siswa dengan pelanggaran aktif" :value="$summary['students_with_active_records']" />
        <x-ui.stat-card label="Catatan aktif" :value="$summary['active_records']" />
        <x-ui.stat-card label="Total poin aktif" :value="$summary['active_points']" />
    </section>

    @if ($students->isEmpty())
        <x-ui.empty-state title="Belum ada siswa" description="Tidak ada siswa yang sesuai dengan penugasan dan pencarian ini." />
    @else
        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600"><tr><th class="px-4 py-3">Siswa</th><th class="px-4 py-3">NISN</th><th class="px-4 py-3">Catatan aktif</th><th class="px-4 py-3">Poin aktif</th><th class="px-4 py-3 text-right">Rincian</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($students as $student)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-950">{{ $student->nama }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $student->nisn }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $student->active_records }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $student->active_points }}</td>
                            <td class="px-4 py-3 text-right"><a class="font-semibold text-gray-900 underline decoration-gray-300 underline-offset-4 hover:decoration-gray-900" href="{{ route('guru.class-recap.show', ['tahunAjaran' => $selectedYear->id, 'kelas' => $selectedClass->id, 'siswa' => $student->id] + request()->query()) }}">Lihat rincian</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
            <div class="border-t border-gray-200 p-4"><x-ui.pagination :paginator="$students" /></div>
        </section>
    @endif
@endsection
