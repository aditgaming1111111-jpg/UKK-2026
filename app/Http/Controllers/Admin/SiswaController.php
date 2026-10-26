<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiswaIndexRequest;
use App\Http\Requests\Admin\StorePenempatanKelasRequest;
use App\Http\Requests\Admin\StoreSiswaRequest;
use App\Http\Requests\Admin\UpdateSiswaRequest;
use App\Http\Requests\Admin\UpdateSiswaStatusRequest;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\SiswaPenempatanService;
use App\Services\SiswaReadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SiswaIndexRequest $request, SiswaReadService $siswaReadService): View
    {
        return view('siswa.index', [
            'students' => $siswaReadService->directory($request->validated()),
            'classes' => $siswaReadService->classes(),
            'levels' => $siswaReadService->levels(),
            'majors' => $siswaReadService->majors(),
            'filters' => $request->validated(),
            'canManageStudents' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $siswa = Siswa::query()->create($request->validated());

        return to_route('admin.students.show', $siswa)->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa, SiswaReadService $siswaReadService): View
    {
        return view('siswa.show', array_merge([
            'student' => $siswa,
            'canManageStudents' => true,
            'canPermanentlyDelete' => ! $siswa->penempatanKelas()->exists() && ! $siswa->catatanPelanggaran()->exists(),
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(),
            'academicYears' => TahunAjaran::query()->orderByDesc('tanggal_mulai')->get(),
        ], $siswaReadService->detail($siswa)));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa): View
    {
        Gate::authorize('update', $siswa);

        return view('siswa.edit', ['student' => $siswa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $siswa->update($request->validated());

        return to_route('admin.students.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        Gate::authorize('delete', $siswa);

        DB::transaction(static function () use ($siswa): void {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();

            if ($lockedStudent->penempatanKelas()->exists() || $lockedStudent->catatanPelanggaran()->exists()) {
                throw ValidationException::withMessages([
                    'siswa' => 'Siswa memiliki riwayat kelas atau pelanggaran sehingga tidak dapat dihapus permanen. Nonaktifkan siswa untuk mempertahankan riwayat.',
                ]);
            }

            $lockedStudent->delete();
        });

        return to_route('admin.students.index')->with('success', 'Data siswa tanpa riwayat berhasil dihapus permanen.');
    }

    public function updateStatus(UpdateSiswaStatusRequest $request, Siswa $siswa): RedirectResponse
    {
        $status = $request->validated('status_siswa');

        DB::transaction(static function () use ($siswa, $status): void {
            Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail()->update([
                'status_siswa' => $status,
            ]);
        });

        $message = $status === 'aktif'
            ? 'Siswa berhasil diaktifkan. Riwayat siswa tetap tersimpan.'
            : 'Siswa dinonaktifkan. Riwayat siswa tetap tersimpan.';

        return to_route('admin.students.show', $siswa)->with('success', $message);
    }

    public function storePlacement(
        StorePenempatanKelasRequest $request,
        Siswa $siswa,
        SiswaPenempatanService $siswaPenempatanService,
    ): RedirectResponse {
        $siswaPenempatanService->assign($siswa, $request->validated());

        return to_route('admin.students.show', $siswa)->with('success', 'Penempatan kelas berhasil disimpan.');
    }
}
