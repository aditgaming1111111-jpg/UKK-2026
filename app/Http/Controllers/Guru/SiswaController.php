<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Services\SiswaReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Menangani permintaan HTTP untuk siswa, termasuk penyajian data dan perubahan yang diizinkan.
 */
class SiswaController extends Controller
{
    /**
     * Menampilkan rincian siswa yang dipilih.
     */
    public function show(Request $request, Siswa $siswa, SiswaReadService $siswaReadService): View
    {
        Gate::authorize('view', $siswa);

        return view('siswa.show', array_merge([
            'student' => $siswa,
            'canManageStudents' => false,
            'classes' => collect(),
            'academicYears' => collect(),
        ], $siswaReadService->detail($siswa)));
    }
}
