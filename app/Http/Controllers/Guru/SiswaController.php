<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiswaIndexRequest;
use App\Models\Guru;
use App\Models\Siswa;
use App\Services\SiswaReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(SiswaIndexRequest $request, SiswaReadService $siswaReadService): View
    {
        return view('siswa.index', [
            'students' => $siswaReadService->directory($request->validated()),
            'classes' => $siswaReadService->classes(),
            'levels' => $siswaReadService->levels(),
            'majors' => $siswaReadService->majors(),
            'filters' => $request->validated(),
            'canManageStudents' => false,
        ]);
    }

    public function show(Request $request, Siswa $siswa, SiswaReadService $siswaReadService): View
    {
        Gate::authorize('view', $siswa);
        $guru = Guru::query()
            ->where('user_id', $request->user()->id)
            ->where('status_aktif', true)
            ->firstOrFail();

        return view('siswa.show', array_merge([
            'student' => $siswa,
            'canManageStudents' => false,
            'classes' => collect(),
            'academicYears' => collect(),
        ], $siswaReadService->detail($siswa, $guru)));
    }
}
