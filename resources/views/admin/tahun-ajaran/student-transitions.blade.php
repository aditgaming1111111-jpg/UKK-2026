@extends('layouts.admin')

@section('title', 'Proses Siswa '.$tahunAjaran->nama_tahun_ajaran)

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[['label' => 'Tahun Ajaran', 'url' => route('admin.academic-years.index')], ['label' => $tahunAjaran->nama_tahun_ajaran, 'url' => route('admin.academic-years.show', $tahunAjaran)], ['label' => 'Proses Siswa']]" />
@endsection

@section('page-heading')
    <x-ui.page-heading title="Kenaikan Kelas & Kelulusan" :description="'Proses siswa dari tahun ajaran '.$tahunAjaran->nama_tahun_ajaran.' dengan pratinjau dan konfirmasi Admin.'" />
@endsection

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <x-ui.toast>{{ session('success') }}</x-ui.toast>
        @endif

        @if ($errors->any())
            <x-ui.error-state title="Proses belum dijalankan" :description="$errors->first()" />
        @endif

        <x-ui.card>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-gray-950">Tahun ajaran asal</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ $tahunAjaran->nama_tahun_ajaran }} · {{ $tahunAjaran->tanggal_mulai->format('d/m/Y') }}–{{ $tahunAjaran->tanggal_selesai->format('d/m/Y') }}</p>
                </div>
                <x-ui.badge :variant="$sourceYearHasEnded ? 'dark' : 'outline'">{{ $sourceYearHasEnded ? 'Periode selesai' : 'Periode belum selesai' }}</x-ui.badge>
            </div>
            @unless ($sourceYearHasEnded)
                <p class="mt-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">Proses baru dapat dijalankan setelah tanggal akhir tahun ajaran asal. Pratinjau tetap tersedia untuk pemeriksaan.</p>
            @endunless
        </x-ui.card>

        <x-ui.card>
            <h2 class="font-semibold text-gray-950">Pilih jenis proses dan tahun tujuan</h2>
            <form method="GET" action="{{ route('admin.academic-years.student-transitions', $tahunAjaran) }}" class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <x-ui.select label="Jenis proses" name="mode" required>
                    <option value="naik" @selected($mode === 'naik')>Kenaikan kelas</option>
                    <option value="tinggal" @selected($mode === 'tinggal')>Tinggal kelas</option>
                    <option value="lulus" @selected($mode === 'lulus')>Kelulusan kelas XII</option>
                </x-ui.select>
                @if ($mode !== 'lulus')
                    <x-ui.select label="Tahun ajaran tujuan" name="tahun_ajaran_tujuan_id" required>
                        <option value="">Pilih tahun ajaran tujuan</option>
                        @foreach ($destinationYears as $destinationYear)
                            <option value="{{ $destinationYear->id }}" @selected((string) ($targetYear?->id ?? old('tahun_ajaran_tujuan_id')) === (string) $destinationYear->id)>
                                {{ $destinationYear->nama_tahun_ajaran }} · mulai {{ $destinationYear->tanggal_mulai->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </x-ui.select>
                    <div class="flex items-end">
                        <x-ui.button type="submit">Tampilkan pratinjau</x-ui.button>
                    </div>
                @else
                    <div class="flex items-end text-sm text-gray-600">Kelulusan tidak membuat penempatan pada tahun tujuan.</div>
                    <div class="flex items-end">
                        <x-ui.button type="submit">Tampilkan pratinjau kelas XII</x-ui.button>
                    </div>
                @endif
            </form>
            <p class="mt-3 text-xs text-gray-500">Kelas tujuan dipilih dari data master kelas. Kenaikan harus menuju satu tingkat di atas kelas asal; tinggal kelas menggunakan tingkat yang sama.</p>
        </x-ui.card>

        @if ($preview)
            @php
                $excludedCount = count(old('dikecualikan', []));
                $selectedCount = max(0, $eligibleCount - $excludedCount);
            @endphp

            <section class="grid gap-4 sm:grid-cols-3">
                <x-ui.stat-card label="Siswa pada pratinjau" :value="$preview['items']->count()" note="Penempatan yang tercatat pada tahun asal" />
                <x-ui.stat-card label="Memenuhi syarat" :value="$eligibleCount" note="Status aktif dan penempatan valid" />
                <x-ui.stat-card label="Akan diproses" :value="$selectedCount" note="Setelah pengecualian yang dipilih" />
            </section>

            @if ($mode !== 'lulus')
                <x-ui.card :padding="false">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h2 class="font-semibold text-gray-950">Pemetaan kelas asal ke kelas tujuan</h2>
                        <p class="mt-1 text-sm text-gray-500">Pilih kelas tujuan untuk setiap kelas asal. Siswa yang memenuhi syarat mengikuti pemetaan kelasnya.</p>
                    </div>
                    @if ($preview['source_classes']->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                    <tr><th class="px-5 py-3 font-medium">Kelas asal</th><th class="px-5 py-3 font-medium">Siswa</th><th class="px-5 py-3 font-medium">Kelas tujuan</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($preview['source_classes'] as $classSummary)
                                        <tr>
                                            <td class="px-5 py-4 font-medium text-gray-900">{{ $classSummary['class']->nama_kelas }} <span class="text-xs text-gray-500">· tingkat {{ $classSummary['class']->tingkat }}</span></td>
                                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $classSummary['eligible_count'] }} memenuhi syarat / {{ $classSummary['total_count'] }} tercatat</td>
                                            <td class="min-w-64 px-5 py-4">
                                                <select name="pemetaan[{{ $classSummary['class']->id }}]" form="student-transition-form" @required($classSummary['eligible_count'] > 0) @disabled($classSummary['eligible_count'] === 0) data-transition-map="{{ $classSummary['class']->id }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                                    <option value="">Pilih kelas tujuan</option>
                                                    @foreach ($classSummary['target_classes'] as $targetClass)
                                                        <option value="{{ $targetClass->id }}" data-target-name="{{ $targetClass->nama_kelas }}" @selected((string) old('pemetaan.'.$classSummary['class']->id) === (string) $targetClass->id)>{{ $targetClass->nama_kelas }} · tingkat {{ $targetClass->tingkat }}{{ $targetClass->jurusan ? ' · '.$targetClass->jurusan : '' }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($classSummary['eligible_count'] === 0)
                                                    <span class="mt-1 block text-xs text-gray-500">Tidak ada calon yang memenuhi syarat.</span>
                                                @elseif ($classSummary['target_classes']->isEmpty())
                                                    <span class="mt-1 block text-xs text-gray-500">Belum ada kelas master pada tingkat tujuan yang sesuai.</span>
                                                @endif
                                                <x-ui.form-error :name="'pemetaan.'.$classSummary['class']->id" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-5"><x-ui.empty-state title="Belum ada penempatan pada tahun asal" description="Tidak ada kelas untuk dipetakan pada periode ini." /></div>
                    @endif
                </x-ui.card>
            @endif

            <x-ui.card :padding="false">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-gray-950">Daftar siswa untuk diperiksa</h2>
                        <p class="mt-1 text-sm text-gray-500">Penempatan asal dipilih berdasarkan kelas yang berlaku pada akhir tahun ajaran asal.</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $preview['items']->count() }} siswa</span>
                </div>
                @if ($preview['items']->isNotEmpty())
                    <form id="student-transition-form" method="POST" action="{{ route('admin.academic-years.student-transitions.store', $tahunAjaran) }}">
                        @csrf
                        <input type="hidden" name="mode" value="{{ $mode }}">
                        @if ($targetYear)
                            <input type="hidden" name="tahun_ajaran_tujuan_id" value="{{ $targetYear->id }}">
                        @endif
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                    <tr><th class="px-5 py-3 font-medium">Siswa</th><th class="px-5 py-3 font-medium">NISN</th><th class="px-5 py-3 font-medium">Kelas asal</th>@if ($mode !== 'lulus')<th class="px-5 py-3 font-medium">Kelas tujuan</th>@endif<th class="px-5 py-3 font-medium">Status proses</th><th class="px-5 py-3 font-medium">Pengecualian</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($preview['items'] as $item)
                                        <tr>
                                            <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">{{ $item['student']->nama }}</td>
                                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $item['student']->nisn }}</td>
                                            <td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $item['source_class']->nama_kelas }}</td>
                                            @if ($mode !== 'lulus')
                                                <td class="whitespace-nowrap px-5 py-4 text-gray-600" data-transition-target-preview="{{ $item['source_class']->id }}">{{ old('pemetaan.'.$item['source_class']->id) ? ($classes->firstWhere('id', (int) old('pemetaan.'.$item['source_class']->id))?->nama_kelas ?? 'Pemetaan tidak valid') : 'Pilih pada pemetaan kelas' }}</td>
                                            @endif
                                            <td class="px-5 py-4">
                                                @if ($item['eligible'])
                                                    <x-ui.badge variant="dark">Memenuhi syarat</x-ui.badge>
                                                @else
                                                    <x-ui.badge variant="outline">Tidak diproses</x-ui.badge>
                                                    <span class="mt-1 block max-w-xs text-xs text-gray-500">{{ $item['reason'] }}</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                @if ($item['eligible'])
                                                    <label class="inline-flex items-center gap-2 text-xs text-gray-700">
                                                        <input type="checkbox" name="dikecualikan[]" value="{{ $item['student']->id }}" @checked(in_array($item['student']->id, array_map('intval', old('dikecualikan', [])), true)) class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                                                        Kecualikan
                                                    </label>
                                                @else
                                                    <span class="text-xs text-gray-400">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 px-5 py-4">
                            <p class="text-sm text-gray-600">{{ $mode === 'lulus' ? 'Siswa lulus tidak mendapat penempatan baru.' : 'Tanggal mulai penempatan baru: '.($targetYear?->tanggal_mulai->format('d/m/Y') ?? '—').'.' }}</p>
                            <x-ui.button type="button" data-modal-open="confirm-student-transition" :disabled="!$sourceYearHasEnded || $eligibleCount === 0">Tinjau & konfirmasi</x-ui.button>
                        </div>
                    </form>
                @else
                    <div class="p-5"><x-ui.empty-state title="Tidak ada data siswa" description="Tahun ajaran ini belum memiliki penempatan yang dapat ditinjau." /></div>
                @endif
            </x-ui.card>

            @if ($preview['items']->where('eligible', true)->isNotEmpty())
                <x-ui.modal id="confirm-student-transition" title="Konfirmasi proses siswa?"
                    :description="'Aksi ini akan memproses maksimal '.$selectedCount.' siswa yang tidak dikecualikan. Penempatan lama tetap disimpan dan aktivitas dicatat.'">
                    <div class="mb-5 rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700">
                        <p><span class="font-medium">Jenis proses:</span> {{ match($mode) { 'naik' => 'Kenaikan kelas', 'tinggal' => 'Tinggal kelas', default => 'Kelulusan kelas XII' } }}</p>
                        <p class="mt-1"><span class="font-medium">Tahun asal:</span> {{ $tahunAjaran->nama_tahun_ajaran }}</p>
                        @if ($targetYear)
                            <p class="mt-1"><span class="font-medium">Tahun tujuan:</span> {{ $targetYear->nama_tahun_ajaran }}</p>
                        @endif
                        <p class="mt-1"><span class="font-medium">Tanggal mulai penempatan baru:</span> {{ $targetYear?->tanggal_mulai->format('d/m/Y') ?? 'Tidak ada untuk kelulusan' }}</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <x-ui.button variant="secondary" type="button" data-modal-close>Batal</x-ui.button>
                        <x-ui.button type="submit" form="student-transition-form" :disabled="!$sourceYearHasEnded || $eligibleCount === 0">Konfirmasi dan proses</x-ui.button>
                    </div>
                </x-ui.modal>
            @endif
        @endif

        <x-ui.card :padding="false">
            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="font-semibold text-gray-950">Riwayat proses tahun asal</h2>
                <p class="mt-1 text-sm text-gray-500">Setiap siswa yang diproses memiliki catatan aktivitas Admin.</p>
            </div>
            @if ($history->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach ($history as $activity)
                        <article class="px-5 py-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-medium text-gray-900">{{ $activity->aktivitas }}</p>
                                <time class="text-xs text-gray-500">{{ $activity->created_at->format('d/m/Y H:i') }}</time>
                            </div>
                            <p class="mt-1 break-words text-sm text-gray-600">{{ $activity->deskripsi }}</p>
                            <p class="mt-2 text-xs text-gray-500">Admin: {{ $activity->pengguna?->nama ?? 'Akun tidak tersedia' }}</p>
                        </article>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 px-5 py-4">
                    <p class="text-sm text-gray-500">Menampilkan {{ $history->firstItem() }}–{{ $history->lastItem() }} dari {{ $history->total() }} catatan.</p>
                    <x-ui.pagination :paginator="$history" />
                </div>
            @else
                <div class="p-5"><x-ui.empty-state title="Belum ada proses" description="Riwayat kenaikan kelas, tinggal kelas, atau kelulusan akan tercatat di sini." /></div>
            @endif
        </x-ui.card>
    </div>

    @if ($preview && $mode !== 'lulus')
        <script>
            document.querySelectorAll('[data-transition-map]').forEach((select) => {
                const sourceClassId = select.dataset.transitionMap;
                const updatePreview = () => {
                    const option = select.options[select.selectedIndex];
                    const targetName = option?.dataset.targetName || 'Pilih pada pemetaan kelas';
                    document.querySelectorAll(`[data-transition-target-preview="${sourceClassId}"]`).forEach((cell) => {
                        cell.textContent = targetName;
                    });
                };
                select.addEventListener('change', updatePreview);
                updatePreview();
            });
        </script>
    @endif
@endsection
