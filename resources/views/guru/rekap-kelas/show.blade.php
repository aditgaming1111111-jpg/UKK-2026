@extends('layouts.guru')

@section('title', 'Rincian Rekap Siswa')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Rekap Kelas Saya', 'url' => route('guru.class-recap.index', ['tahun_ajaran_id' => $selectedYear->id, 'kelas_id' => $selectedClass->id])], ['label' => 'Rincian siswa']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Rincian Pelanggaran Siswa" :description="$student->nama . ' · NISN ' . $student->nisn" />
@endsection

@section('content')
    <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <dl class="grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
            <div><dt class="text-gray-500">Siswa</dt><dd class="mt-1 font-semibold text-gray-950">{{ $student->nama }}</dd></div>
            <div><dt class="text-gray-500">NISN</dt><dd class="mt-1 font-semibold text-gray-950">{{ $student->nisn }}</dd></div>
            <div><dt class="text-gray-500">Kelas saat kejadian</dt><dd class="mt-1 font-semibold text-gray-950">{{ $selectedClass->nama_kelas }}</dd></div>
            <div><dt class="text-gray-500">Tahun ajaran</dt><dd class="mt-1 font-semibold text-gray-950">{{ $selectedYear->nama_tahun_ajaran }}</dd></div>
        </dl>
    </section>

    <section class="grid gap-4 sm:grid-cols-2"><x-ui.stat-card label="Catatan aktif" :value="$summary['active_records']" /><x-ui.stat-card label="Total poin aktif" :value="$summary['active_points']" /></section>

    @if ($records->isEmpty())
        <x-ui.empty-state title="Belum ada catatan pelanggaran" description="Siswa ini belum memiliki catatan dalam penugasan dan periode yang dipilih." />
    @else
        <div class="space-y-4">
            @foreach ($records as $record)
                <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="font-semibold text-gray-950">{{ $record->pelanggaran?->nama_pelanggaran ?? 'Jenis pelanggaran tidak tersedia' }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ $record->pelanggaran?->kategori?->nama_kategori ?? 'Kategori tidak tersedia' }} · {{ $record->tanggal_pelanggaran?->format('d/m/Y') }} · {{ $record->penempatanKelas?->kelas?->nama_kelas }} · {{ $record->penempatanKelas?->tahunAjaran?->nama_tahun_ajaran }}</p>
                        </div>
                        <span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $record->status === 'aktif' ? 'border-gray-300 bg-gray-100 text-gray-800' : 'border-gray-400 bg-gray-200 text-gray-700' }}">{{ ucfirst($record->status) }}</span>
                    </div>
                    <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                        <div><dt class="text-gray-500">Poin saat dicatat</dt><dd class="mt-1 font-medium text-gray-950">{{ $record->poin_saat_dicatat }}</dd></div>
                        <div><dt class="text-gray-500">Guru pencatat</dt><dd class="mt-1 font-medium text-gray-950">{{ $record->guru?->nama ?? 'Tidak tersedia' }}</dd></div>
                        <div><dt class="text-gray-500">Keterangan kejadian</dt><dd class="mt-1 whitespace-pre-line text-gray-800">{{ $record->keterangan ?: '—' }}</dd></div>
                        <div><dt class="text-gray-500">Tindakan</dt><dd class="mt-1 whitespace-pre-line text-gray-800">{{ $record->tindakan ?: '—' }}</dd></div>
                    </dl>
                    @if ($record->logPerubahan->isNotEmpty())
                        <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h3 class="text-sm font-semibold text-gray-900">Riwayat perubahan</h3>
                            @forelse ($record->logPerubahan as $log)
                                <div class="mt-3 border-t border-gray-200 pt-3 text-sm">
                                    <p class="font-medium text-gray-900">{{ ucfirst($log->aksi) }} oleh {{ $log->pengguna?->nama ?? 'Pengguna tidak tersedia' }} · {{ $log->created_at?->format('d/m/Y H:i') }}</p>
                                    @if ($log->alasan)<p class="mt-1 text-gray-700">Alasan: {{ $log->alasan }}</p>@endif
                                </div>
                            @empty
                                <p class="mt-2 text-sm text-gray-600">Log audit tidak tersedia.</p>
                            @endforelse
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4"><x-ui.pagination :paginator="$records" /></div>
    @endif
@endsection
