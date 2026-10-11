<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\SearchSiswaPenempatanRequest;
use App\Http\Requests\Guru\StorePelanggaranSiswaRequest;
use App\Models\KelasSiswa;
use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use App\Services\PencatatanPelanggaranService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PelanggaranSiswaController extends Controller
{
    public function create(): View
    {
        $violations = Pelanggaran::availableForRecording()
            ->with('kategori:id,nama_kategori')
            ->orderBy('kategori_id')
            ->orderBy('nama_pelanggaran')
            ->get(['id', 'kategori_id', 'kode', 'nama_pelanggaran', 'poin']);

        return view('guru.pelanggaran.create', [
            'categories' => PelanggaranKategori::query()
                ->where('status_aktif', true)
                ->orderBy('nama_kategori')
                ->get(['id', 'nama_kategori']),
            'violations' => $violations,
        ]);
    }

    public function searchStudents(SearchSiswaPenempatanRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $date = CarbonImmutable::parse($validated['tanggal_pelanggaran'])->toDateString();
        $term = addcslashes(trim($validated['q']), '%_\\');

        $placements = KelasSiswa::query()
            ->with([
                'siswa:id,nisn,nama',
                'kelas:id,nama_kelas,tingkat,jurusan',
                'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai',
            ])
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($query) use ($date): void {
                $query->whereNull('tanggal_selesai')->orWhereDate('tanggal_selesai', '>=', $date);
            })
            ->whereHas('tahunAjaran', function ($query) use ($date): void {
                $query->whereDate('tanggal_mulai', '<=', $date)
                    ->whereDate('tanggal_selesai', '>=', $date);
            })
            ->whereHas('siswa', function ($query) use ($term): void {
                $query->where('nisn', 'like', '%'.$term.'%')
                    ->orWhere('nama', 'like', '%'.$term.'%');
            })
            ->orderByDesc('tanggal_mulai')
            ->limit(20)
            ->get();

        return response()->json($placements->map(static fn (KelasSiswa $placement): array => [
            'placement_id' => $placement->id,
            'student' => [
                'name' => $placement->siswa->nama,
                'nisn' => $placement->siswa->nisn,
            ],
            'class' => [
                'name' => $placement->kelas->nama_kelas,
                'level' => $placement->kelas->tingkat,
                'major' => $placement->kelas->jurusan,
            ],
            'academic_year' => $placement->tahunAjaran->nama_tahun_ajaran,
        ])->values());
    }

    public function store(StorePelanggaranSiswaRequest $request, PencatatanPelanggaranService $service): RedirectResponse
    {
        $record = $service->record($request->user(), $request->validated());
        $student = $record->penempatanKelas->siswa;

        return redirect()
            ->route('guru.students.show', $student)
            ->with('success', 'Catatan pelanggaran berhasil disimpan.');
    }
}
