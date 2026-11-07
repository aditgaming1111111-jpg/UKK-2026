<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiswaIndexRequest;
use App\Http\Requests\Admin\StorePenempatanKelasRequest;
use App\Http\Requests\Admin\StoreSiswaRequest;
use App\Http\Requests\Admin\UpdatePenempatanKelasRequest;
use App\Http\Requests\Admin\UpdateSiswaRequest;
use App\Http\Requests\Admin\UpdateSiswaStatusRequest;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\ActivityLogger;
use App\Services\SiswaPenempatanService;
use App\Services\SiswaReadService;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $filters = $request->validated();
        $academicYears = $siswaReadService->academicYears();
        $selectedAcademicYear = isset($filters['tahun_ajaran_id'])
            ? $academicYears->firstWhere('id', (int) $filters['tahun_ajaran_id'])
            : ($academicYears->firstWhere('status_aktif', true) ?? $academicYears->first());
        $filters['tahun_ajaran_id'] = $selectedAcademicYear?->id;

        return view('siswa.index', [
            'students' => $siswaReadService->directory($filters, $request->user()),
            'classes' => $siswaReadService->classes(),
            'levels' => $siswaReadService->levels(),
            'majors' => $siswaReadService->majors(),
            'filters' => $filters,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
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
    public function store(StoreSiswaRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $siswa = Siswa::query()->create($request->validated());
        $activityLogger->record($request->user(), $request, 'Siswa ditambahkan', 'Identitas siswa ID #'.$siswa->id.' berhasil ditambahkan.');

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
            'canPermanentlyDelete' => $siswa->status_siswa !== 'lulus'
                && ! $siswa->penempatanKelas()->exists()
                && ! $siswa->catatanPelanggaran()->exists(),
            'canGraduate' => $siswa->status_siswa !== 'lulus' && $siswa->hasCompletedGradeTwelvePlacement(),
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
    public function update(UpdateSiswaRequest $request, Siswa $siswa, ActivityLogger $activityLogger): RedirectResponse
    {
        $siswa->update($request->validated());
        $activityLogger->record($request->user(), $request, 'Siswa diperbarui', 'Data siswa ID #'.$siswa->id.' berhasil diperbarui.');

        return to_route('admin.students.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('delete', $siswa);

        DB::transaction(static function () use ($siswa): void {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();

            if ($lockedStudent->status_siswa === 'lulus'
                || $lockedStudent->penempatanKelas()->exists()
                || $lockedStudent->catatanPelanggaran()->exists()) {
                throw ValidationException::withMessages([
                    'siswa' => 'Siswa berstatus lulus atau memiliki riwayat kelas/pelanggaran sehingga tidak dapat dihapus permanen. Arsipkan dengan mempertahankan data siswa.',
                ]);
            }

            $lockedStudent->delete();
        });
        $activityLogger->record($request->user(), $request, 'Siswa dihapus', 'Identitas siswa ID #'.$siswa->id.' tanpa histori berhasil dihapus.');

        return to_route('admin.students.index')->with('success', 'Data siswa tanpa riwayat berhasil dihapus permanen.');
    }

    public function updateStatus(UpdateSiswaStatusRequest $request, Siswa $siswa, ActivityLogger $activityLogger): RedirectResponse
    {
        $status = $request->validated('status_siswa');

        DB::transaction(static function () use ($siswa, $status): void {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();

            if ($status === 'lulus' && ! $lockedStudent->hasCompletedGradeTwelvePlacement()) {
                throw ValidationException::withMessages([
                    'status_siswa' => 'Siswa hanya dapat ditandai lulus setelah penempatan kelas XII selesai dan tahun ajaran berakhir.',
                ]);
            }

            $lockedStudent->update(['status_siswa' => $status]);
        });
        $activityLogger->record($request->user(), $request, 'Status siswa diubah', 'Status siswa ID #'.$siswa->id.' diubah menjadi '.$status.'.');

        $message = match ($status) {
            'aktif' => 'Siswa berhasil diaktifkan. Riwayat siswa tetap tersimpan.',
            'nonaktif' => 'Siswa dinonaktifkan. Riwayat siswa tetap tersimpan.',
            'pindah' => 'Status siswa diperbarui menjadi pindah. Riwayat siswa tetap tersimpan.',
            'keluar' => 'Status siswa diperbarui menjadi keluar. Riwayat siswa tetap tersimpan.',
            'lulus' => 'Siswa ditandai lulus dan tersimpan sebagai arsip alumni. Riwayat tetap tersimpan.',
        };

        return to_route('admin.students.show', $siswa)->with('success', $message);
    }

    public function storePlacement(
        StorePenempatanKelasRequest $request,
        Siswa $siswa,
        SiswaPenempatanService $siswaPenempatanService,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        $placement = $siswaPenempatanService->assign($siswa, $request->validated());
        $activityLogger->record($request->user(), $request, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #'.$siswa->id.' dicatat dengan ID #'.$placement->id.'.');

        return to_route('admin.students.show', $siswa)->with('success', 'Penempatan kelas berhasil disimpan.');
    }

    public function updatePlacement(
        UpdatePenempatanKelasRequest $request,
        Siswa $siswa,
        int $placementId,
        SiswaPenempatanService $siswaPenempatanService,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        DB::transaction(function () use ($request, $siswa, $placementId, $siswaPenempatanService, $activityLogger): void {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();
            $placement = $lockedStudent->penempatanKelas()
                ->with(['tahunAjaran:id,tanggal_mulai,tanggal_selesai', 'siswa:id'])
                ->whereKey($placementId)
                ->lockForUpdate()
                ->firstOrFail();
            $startDate = CarbonImmutable::parse($request->validated('tanggal_mulai'))->startOfDay();
            $endDateValue = $request->validated('tanggal_selesai');
            $endDate = $endDateValue !== null ? CarbonImmutable::parse($endDateValue)->startOfDay() : null;

            $siswaPenempatanService->assertCorrectionIsValid($placement, $startDate, $endDate);

            $beforeStartDate = $placement->tanggal_mulai?->format('Y-m-d') ?? 'kosong';
            $beforeEndDate = $placement->tanggal_selesai?->format('Y-m-d') ?? 'kosong';

            $placement->update([
                'tanggal_mulai' => $startDate->toDateString(),
                'tanggal_selesai' => $endDate?->toDateString(),
            ]);

            $activityLogger->record(
                $request->user(),
                $request,
                'Tanggal penempatan siswa dikoreksi',
                sprintf(
                    'Admin ID #%d mengoreksi tanggal penempatan #%d siswa %s (NISN %s). Sebelum: mulai %s, selesai %s. Sesudah: mulai %s, selesai %s.',
                    $request->user()->id,
                    $placement->id,
                    $lockedStudent->nama,
                    $lockedStudent->nisn,
                    $beforeStartDate,
                    $beforeEndDate,
                    $startDate->toDateString(),
                    $endDate?->toDateString() ?? 'kosong',
                ),
            );
        });

        return to_route('admin.students.show', $siswa)->with('success', 'Tanggal penempatan berhasil dikoreksi dan dicatat di log aktivitas.');
    }

    public function destroyPlacement(
        Request $request,
        Siswa $siswa,
        int $placementId,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        Gate::authorize('managePlacements', $siswa);

        DB::transaction(function () use ($request, $siswa, $placementId, $activityLogger): void {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();
            $placement = $lockedStudent->penempatanKelas()
                ->with(['kelas:id,nama_kelas', 'tahunAjaran:id,nama_tahun_ajaran'])
                ->whereKey($placementId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($placement->catatanPelanggaran()->exists()) {
                throw ValidationException::withMessages([
                    'placement' => 'Riwayat penempatan ini masih terkait dengan catatan pelanggaran dan tidak dapat dihapus.',
                ]);
            }

            $hasReplacementPlacement = $lockedStudent->penempatanKelas()
                ->whereKeyNot($placement->id)
                ->where('status_aktif', true)
                ->whereNull('tanggal_selesai')
                ->exists();

            if ($lockedStudent->status_siswa === 'aktif' && ! $hasReplacementPlacement) {
                throw ValidationException::withMessages([
                    'placement' => 'Siswa aktif harus memiliki penempatan pengganti sebelum riwayat ini dihapus. Tambahkan penempatan pengganti atau nonaktifkan siswa melalui tindakan yang tersedia.',
                ]);
            }

            $startDate = $placement->tanggal_mulai?->format('d/m/Y') ?? 'tidak tersedia';
            $endDate = $placement->tanggal_selesai?->format('d/m/Y') ?? 'sekarang';
            $description = sprintf(
                'Admin ID #%d menghapus penempatan #%d milik siswa %s (NISN %s), kelas %s, tahun ajaran %s, periode %s sampai %s.',
                $request->user()->id,
                $placement->id,
                $lockedStudent->nama,
                $lockedStudent->nisn,
                $placement->kelas?->nama_kelas ?? 'kelas tidak tersedia',
                $placement->tahunAjaran?->nama_tahun_ajaran ?? 'tahun ajaran tidak tersedia',
                $startDate,
                $endDate,
            );

            $placement->delete();
            $activityLogger->record($request->user(), $request, 'Riwayat penempatan siswa dihapus', $description);
        });

        return to_route('admin.students.show', $siswa)->with('success', 'Riwayat penempatan berhasil dihapus. Riwayat lain tetap tersimpan.');
    }
}
