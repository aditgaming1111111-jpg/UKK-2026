<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanIndexRequest;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\TahunAjaran;
use App\Services\LaporanPelanggaranService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(LaporanIndexRequest $request, LaporanPelanggaranService $service): View
    {
        $filters = $request->validated();
        $selectedYear = $this->selectedYear($filters);
        $filters['tahun_ajaran_id'] = $selectedYear?->id;
        $query = $service->query($filters);
        $summary = $service->summary($query);
        $records = (clone $query)->paginate(15)->withQueryString();

        return view('admin.laporan.index', [
            'records' => $records,
            'summary' => $summary,
            'filters' => $filters,
            'filterLabels' => $this->filterLabels($filters, $selectedYear),
            'academicYears' => TahunAjaran::query()->orderByDesc('tanggal_mulai')->get(['id', 'nama_tahun_ajaran', 'status_aktif']),
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas', 'tingkat']),
            'violations' => Pelanggaran::query()->orderBy('nama_pelanggaran')->get(['id', 'kode', 'nama_pelanggaran', 'status_aktif']),
        ]);
    }

    public function print(LaporanIndexRequest $request, LaporanPelanggaranService $service): View
    {
        return view('laporan.print', $this->printData($request->validated(), $service));
    }

    public function pdf(LaporanIndexRequest $request, LaporanPelanggaranService $service): Response
    {
        $generatedAt = now();
        $data = $this->printData($request->validated(), $service, $generatedAt);
        $filename = 'laporan-pelanggaran-'.$generatedAt->format('Ymd-His').'.pdf';

        return Pdf::loadView('laporan.print', $data)
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    /** @param array<string, int|string|null> $filters
     * @return array<string, mixed>
     */
    private function printData(array $filters, LaporanPelanggaranService $service, ?Carbon $generatedAt = null): array
    {
        $selectedYear = $this->selectedYear($filters);
        $filters['tahun_ajaran_id'] = $selectedYear?->id;
        $query = $service->query($filters);

        return [
            'records' => (clone $query)->get(),
            'summary' => $service->summary($query),
            'filterLabels' => $this->filterLabels($filters, $selectedYear),
            'selectedYear' => $selectedYear,
            'generatedAt' => $generatedAt ?? now(),
            'reportTitle' => 'Laporan Pelanggaran Siswa',
        ];
    }

    /** @param array<string, int|string|null> $filters */
    private function selectedYear(array $filters): ?TahunAjaran
    {
        if (! empty($filters['tahun_ajaran_id'])) {
            return TahunAjaran::query()->findOrFail($filters['tahun_ajaran_id']);
        }

        return TahunAjaran::query()
            ->orderByDesc('status_aktif')
            ->orderByDesc('tanggal_mulai')
            ->first();
    }

    /** @param array<string, int|string|null> $filters
     * @return array<string, string>
     */
    private function filterLabels(array $filters, ?TahunAjaran $selectedYear): array
    {
        $class = ! empty($filters['kelas_id']) ? Kelas::query()->find($filters['kelas_id']) : null;
        $violation = ! empty($filters['pelanggaran_id']) ? Pelanggaran::query()->find($filters['pelanggaran_id']) : null;

        return [
            'Tahun ajaran' => $selectedYear?->nama_tahun_ajaran ?? 'Semua tahun ajaran',
            'Kelas kejadian' => $class?->nama_kelas ?? 'Semua kelas',
            'Siswa' => ($filters['q'] ?? '') !== '' ? (string) $filters['q'] : 'Semua siswa',
            'Rentang kejadian' => ($filters['tanggal_mulai'] ?? null) || ($filters['tanggal_selesai'] ?? null)
                ? (($filters['tanggal_mulai'] ?? 'Awal').' — '.($filters['tanggal_selesai'] ?? 'Hari ini'))
                : 'Semua tanggal',
            'Jenis pelanggaran' => $violation?->nama_pelanggaran ?? 'Semua jenis',
            'Status' => match ($filters['status'] ?? null) {
                'aktif' => 'Aktif',
                'dibatalkan' => 'Dibatalkan',
                default => 'Semua status',
            },
        ];
    }
}
