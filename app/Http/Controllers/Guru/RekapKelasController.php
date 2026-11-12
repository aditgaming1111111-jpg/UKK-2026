<?php

namespace App\Http\Controllers\Guru;

use App\Exports\RekapWaliKelasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RekapKelasRequest;
use App\Models\Siswa;
use App\Services\RekapWaliKelasReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Menangani permintaan HTTP untuk rekap kelas, termasuk penyajian data dan perubahan yang diizinkan.
 */
class RekapKelasController extends Controller
{
    /**
     * Menampilkan daftar rekap kelas dengan filter dan paginasi yang berlaku.
     */
    public function index(RekapKelasRequest $request, RekapWaliKelasReport $report): View
    {
        return view('guru.rekap-kelas.index', [
            ...$report->index($request->user(), $request->validated()),
        ]);
    }

    /**
     * Menampilkan rincian rekap kelas yang dipilih.
     */
    public function show(Request $request, RekapWaliKelasReport $report, int $tahunAjaran, int $kelas, Siswa $siswa): View
    {
        $filters = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', Rule::when($request->filled('tanggal_mulai'), ['after_or_equal:tanggal_mulai'])],
        ]);

        return view('guru.rekap-kelas.show', [
            ...$report->student($request->user(), $tahunAjaran, $kelas, $siswa, $filters),
        ]);
    }

    /**
     * Menghasilkan dokumen PDF dari rekap kelas yang dipilih.
     */
    public function pdf(Request $request, RekapWaliKelasReport $report, int $tahunAjaran, int $kelas): Response
    {
        $data = $report->exportData($request->user(), $tahunAjaran, $kelas, $this->exportFilters($request));
        $data['records'] = $data['records']->get();
        $data['generatedAt'] = now();

        return Pdf::loadView('guru.rekap-kelas.print', $data)
            ->setPaper('a4', 'landscape')
            ->download('rekap-wali-kelas-'.$tahunAjaran.'-'.$kelas.'-'.now()->format('Ymd-His').'.pdf');
    }

    /**
     * Menghasilkan lembar kerja dari rekap kelas yang dipilih.
     */
    public function excel(Request $request, RekapWaliKelasReport $report, int $tahunAjaran, int $kelas): BinaryFileResponse
    {
        $data = $report->exportData($request->user(), $tahunAjaran, $kelas, $this->exportFilters($request));
        $data['generatedAt'] = now();

        return Excel::download(
            new RekapWaliKelasExport($data),
            'rekap-wali-kelas-'.$tahunAjaran.'-'.$kelas.'-'.now()->format('Ymd-His').'.xlsx',
        );
    }

    /** @return array<string, string|null> */
    private function exportFilters(Request $request): array
    {
        return $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', Rule::when($request->filled('tanggal_mulai'), ['after_or_equal:tanggal_mulai'])],
        ]);
    }
}
