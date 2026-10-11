@extends('layouts.guru')

@section('title', 'Rekap Kelas Saya')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Rekap Kelas Saya']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Rekap Kelas Saya" description="Rekap hanya untuk kelas dan tahun ajaran sesuai penugasan wali kelas Anda." />
@endsection

@section('content')
    <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex flex-wrap gap-2">@foreach ($assignments as $assignment)<span class="rounded-full border border-gray-300 px-3 py-1 text-sm">{{ $assignment->kelas->nama_kelas }}</span>@endforeach</div>
        <form method="GET" action="{{ route('guru.class-recap.index') }}" class="flex flex-col gap-3 sm:flex-row">
            <label class="text-sm font-medium">Tahun ajaran
                <select name="tahun_ajaran_id" class="mt-1 min-w-56 rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                    @foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) ($filters['tahun_ajaran_id'] ?? $selectedYear->id) === $year->id)>{{ $year->nama_tahun_ajaran }}</option>@endforeach
                </select>
            </label>
            <label class="flex-1 text-sm font-medium">Cari nama atau NISN
                <input name="q" value="{{ $filters['q'] ?? '' }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900" placeholder="Cari siswa...">
            </label>
            <button class="self-end rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Terapkan</button>
        </form>
    </section>

    <section class="grid gap-4 sm:grid-cols-2"><x-ui.stat-card label="Catatan aktif kelas" :value="$summary['active_records']" /><x-ui.stat-card label="Total poin aktif kelas" :value="$summary['active_points']" /></section>

    @if ($students->isEmpty())
        <x-ui.empty-state title="Belum ada siswa" description="Tidak ada siswa yang sesuai dengan penugasan dan pencarian ini." />
    @else
        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600"><tr><th class="px-4 py-3">Siswa</th><th class="px-4 py-3">NISN</th><th class="px-4 py-3">Kelas pada periode</th><th class="px-4 py-3">Catatan aktif</th><th class="px-4 py-3">Poin aktif</th></tr></thead>
                <tbody class="divide-y divide-gray-100">@foreach ($students as $placement)<tr><td class="px-4 py-3 font-medium">{{ $placement->siswa->nama }}</td><td class="px-4 py-3">{{ $placement->siswa->nisn }}</td><td class="px-4 py-3">{{ $placement->kelas->nama_kelas }}</td><td class="px-4 py-3">{{ $placement->active_records }}</td><td class="px-4 py-3">{{ $placement->active_points }}</td></tr>@endforeach</tbody>
            </table></div>
            <div class="border-t border-gray-200 p-4">{{ $students->links() }}</div>
        </section>
    @endif
@endsection
