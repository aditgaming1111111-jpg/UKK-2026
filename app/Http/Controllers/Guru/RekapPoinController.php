<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\PointRecapRequest;
use App\Services\SiswaReadService;
use Illuminate\View\View;

/**
 * Menangani permintaan HTTP untuk rekap poin, termasuk penyajian data dan perubahan yang diizinkan.
 */
class RekapPoinController extends Controller
{
    /**
     * Menampilkan daftar rekap poin dengan filter dan paginasi yang berlaku.
     */
    public function index(PointRecapRequest $request, SiswaReadService $siswaReadService): View
    {
        $filters = $request->validated();
        $academicYears = $siswaReadService->academicYears();
        $selectedAcademicYear = isset($filters['tahun_ajaran_id'])
            ? $academicYears->firstWhere('id', (int) $filters['tahun_ajaran_id'])
            : ($academicYears->firstWhere('status_aktif', true) ?? $academicYears->first());
        $filters['tahun_ajaran_id'] = $selectedAcademicYear?->id;

        return view('guru.rekap-poin.index', [
            'students' => $siswaReadService->pointRecap($filters),
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'filters' => $filters,
        ]);
    }
}
