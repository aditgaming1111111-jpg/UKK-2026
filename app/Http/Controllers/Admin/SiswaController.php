<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MoveSiswaClassRequest;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Menangani permintaan HTTP untuk siswa, termasuk penyajian data dan perubahan yang diizinkan.
 */
class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa dengan filter dan paginasi yang berlaku.
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
     * Menampilkan arsip siswa yang telah berstatus keluar.
     */
    public function archivedIndex(Request $request): View
    {
        Gate::authorize('viewAny', Siswa::class);
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);

        $students = Siswa::onlyTrashed()
            ->where('status_siswa', 'keluar')
            ->when($filters['q'] ?? null, static fn (Builder $query, string $search): Builder => $query->where(static function (Builder $searchQuery) use ($search): void {
                $searchQuery->where('nis', 'like', '%'.$search.'%')
                    ->orWhere('nisn', 'like', '%'.$search.'%')
                    ->orWhere('nama', 'like', '%'.$search.'%');
            }))
            ->orderBy('nama')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('siswa.archived-index', compact('students', 'filters'));
    }

    /**
     * Menampilkan arsip siswa beserta histori yang masih terhubung.
     */
    public function showArchived(int $studentId, SiswaReadService $siswaReadService): View
    {
        Gate::authorize('viewAny', Siswa::class);
        $student = Siswa::onlyTrashed()->where('status_siswa', 'keluar')->findOrFail($studentId);

        return view('siswa.show', array_merge([
            'student' => $student,
            'canManageStudents' => false,
            'canPermanentlyDelete' => false,
            'canGraduate' => false,
            'isArchived' => true,
            'classes' => collect(),
            'academicYears' => collect(),
        ], $siswaReadService->detail($student)));
    }

    /**
     * Menampilkan formulir untuk membuat siswa.
     */
    public function create(): View
    {
        return view('siswa.create');
    }

    /**
     * Memvalidasi dan menyimpan siswa baru.
     */
    public function store(StoreSiswaRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $siswa = Siswa::query()->create($request->validated());
        $activityLogger->record($request->user(), $request, 'Siswa ditambahkan', 'Identitas siswa ID #'.$siswa->id.' berhasil ditambahkan.');

        return to_route('admin.students.show', $siswa)->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan rincian siswa yang dipilih.
     */
    public function show(Siswa $siswa, SiswaReadService $siswaReadService): View
    {
        $studentDetails = $siswaReadService->detail($siswa);
        $openPlacements = $studentDetails['placements']->filter(
            static fn ($placement): bool => $placement->status_aktif || $placement->tanggal_selesai === null,
        )->values();
        $currentPlacement = $openPlacements->count() === 1
            && $openPlacements->first()->status_aktif
            && $openPlacements->first()->tanggal_selesai === null
            ? $openPlacements->first()
            : null;

        return view('siswa.show', array_merge([
            'student' => $siswa,
            'canManageStudents' => true,
            'isArchived' => false,
            'canPermanentlyDelete' => ! in_array($siswa->status_siswa, ['keluar', 'lulus'], true)
                && ! $siswa->penempatanKelas()->exists()
                && ! $siswa->catatanPelanggaran()->exists(),
            'canGraduate' => $siswa->status_siswa !== 'lulus' && $siswa->hasCompletedGradeTwelvePlacement(),
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(),
            'academicYears' => TahunAjaran::query()->where('status_aktif', true)->orderByDesc('tanggal_mulai')->get(),
            'activePlacementCount' => $openPlacements->count(),
            'currentPlacement' => $currentPlacement,
        ], $studentDetails));
    }

    /**
     * Menyiapkan formulir perubahan untuk siswa yang dipilih.
     */
    public function edit(Siswa $siswa): View
    {
        Gate::authorize('update', $siswa);

        return view('siswa.edit', ['student' => $siswa]);
    }

    /**
     * Memvalidasi dan menyimpan perubahan pada siswa.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa, ActivityLogger $activityLogger): RedirectResponse
    {
        $siswa->update($request->validated());
        $activityLogger->record($request->user(), $request, 'Siswa diperbarui', 'Data siswa ID #'.$siswa->id.' berhasil diperbarui.');

        return to_route('admin.students.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus siswa setelah pemeriksaan otorisasi dan aturan terkait.
     */
    public function destroy(Siswa $siswa, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('delete', $siswa);

        DB::transaction(static function () use ($siswa): void {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();

            if (
                in_array($lockedStudent->status_siswa, ['keluar', 'lulus'], true)
                || $lockedStudent->penempatanKelas()->exists()
                || $lockedStudent->catatanPelanggaran()->exists()
            ) {
                throw ValidationException::withMessages([
                    'siswa' => 'Siswa berstatus keluar/lulus atau memiliki riwayat kelas/pelanggaran sehingga tidak dapat dihapus permanen.',
                ]);
            }

            $lockedStudent->forceDelete();
        });
        $activityLogger->record($request->user(), $request, 'Siswa dihapus', 'Identitas siswa ID #'.$siswa->id.' tanpa histori berhasil dihapus.');

        return to_route('admin.students.index')->with('success', 'Data siswa tanpa riwayat berhasil dihapus permanen.');
    }

    /**
     * Memperbarui status siswa melalui alur yang diotorisasi.
     */
    public function updateStatus(
        UpdateSiswaStatusRequest $request,
        Siswa $siswa,
        SiswaPenempatanService $siswaPenempatanService,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        $status = $request->validated('status_siswa');

        [$previousStatus, $closedPlacementCount] = DB::transaction(function () use ($request, $siswa, $status, $siswaPenempatanService, $activityLogger): array {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();

            if ($status === 'lulus' && ! $lockedStudent->hasCompletedGradeTwelvePlacement()) {
                throw ValidationException::withMessages([
                    'status_siswa' => 'Siswa hanya dapat ditandai lulus setelah penempatan kelas XII selesai dan tahun ajaran berakhir.',
                ]);
            }

            $previousStatus = $lockedStudent->status_siswa;
            $mustEndPlacements = in_array($status, ['keluar', 'lulus'], true)
                || ($status === 'aktif' && in_array($previousStatus, ['keluar', 'lulus'], true));
            $closedPlacementCount = $mustEndPlacements
                ? $siswaPenempatanService->endActivePlacements($lockedStudent, CarbonImmutable::today())
                : 0;
            $lockedStudent->update(['status_siswa' => $status]);
            $activityLogger->record(
                $request->user(),
                $request,
                'Status siswa diubah',
                sprintf('Status siswa ID #%d (%s, NISN %s) diubah dari %s menjadi %s. Penempatan aktif yang diakhiri: %d.', $lockedStudent->id, $lockedStudent->nama, $lockedStudent->nisn, $previousStatus, $status, $closedPlacementCount),
            );

            return [$previousStatus, $closedPlacementCount];
        });

        $message = match ($status) {
            'aktif' => in_array($previousStatus, ['keluar', 'lulus'], true)
                ? 'Siswa berhasil diaktifkan kembali. Belum ada penempatan kelas; Admin perlu membuat penempatan baru.'
                : 'Siswa berhasil diaktifkan. Riwayat siswa tetap tersimpan.',
            'nonaktif' => 'Siswa dinonaktifkan. Riwayat siswa tetap tersimpan.',
            'keluar' => 'Status siswa diperbarui menjadi keluar dan penempatan aktif diakhiri. Riwayat tetap tersimpan.',
            'lulus' => 'Siswa ditandai lulus dan penempatan aktif diakhiri. Riwayat tetap tersimpan.',
        };

        return to_route('admin.students.show', $siswa)->with('success', $message);
    }

    /**
     * Mengarsipkan siswa berstatus keluar tanpa menghapus relasi dan histori.
     */
    public function archive(Siswa $siswa, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('archive', $siswa);

        DB::transaction(function () use ($siswa, $request, $activityLogger): void {
            $lockedStudent = Siswa::query()->whereKey($siswa->id)->lockForUpdate()->firstOrFail();

            if ($lockedStudent->status_siswa !== 'keluar') {
                throw ValidationException::withMessages([
                    'siswa' => 'Hanya siswa berstatus keluar yang dapat diarsipkan.',
                ]);
            }

            $lockedStudent->delete();
            $activityLogger->record(
                $request->user(),
                $request,
                'Siswa diarsipkan',
                sprintf('Siswa ID #%d (%s, NISN %s) berstatus keluar diarsipkan. Data penempatan dan pelanggaran dipertahankan.', $lockedStudent->id, $lockedStudent->nama, $lockedStudent->nisn),
            );
        });

        return to_route('admin.students.archived.index')->with('success', 'Siswa Keluar berhasil diarsipkan. Riwayat tetap tersimpan.');
    }

    /**
     * Memulihkan siswa keluar yang sebelumnya diarsipkan.
     */
    public function restore(int $studentId, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('restoreArchive', Siswa::class);

        DB::transaction(function () use ($studentId, $request, $activityLogger): void {
            $student = Siswa::onlyTrashed()->whereKey($studentId)->lockForUpdate()->firstOrFail();

            if ($student->status_siswa !== 'keluar') {
                throw ValidationException::withMessages([
                    'siswa' => 'Hanya arsip siswa berstatus keluar yang dapat dipulihkan.',
                ]);
            }

            $student->restore();
            $activityLogger->record(
                $request->user(),
                $request,
                'Arsip siswa dipulihkan',
                sprintf('Arsip siswa ID #%d (%s, NISN %s) berstatus keluar dipulihkan.', $student->id, $student->nama, $student->nisn),
            );
        });

        return to_route('admin.students.archived.index')->with('success', 'Arsip siswa berhasil dipulihkan.');
    }

    /**
     * Menyimpan penempatan kelas baru bagi siswa.
     */
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

    /**
     * Memindahkan siswa ke kelas lain pada periode penempatan saat ini.
     */
    public function moveClass(
        MoveSiswaClassRequest $request,
        Siswa $siswa,
        SiswaPenempatanService $siswaPenempatanService,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        DB::transaction(function () use ($request, $siswa, $siswaPenempatanService, $activityLogger): void {
            $placements = $siswaPenempatanService->transfer($siswa, $request->validated());
            $previousPlacement = $placements['previous'];
            $currentPlacement = $placements['current'];
            $previousClass = $previousPlacement->kelas;
            $currentClass = $currentPlacement->kelas;

            $activityLogger->record(
                $request->user(),
                $request,
                'Siswa pindah kelas',
                sprintf(
                    'Siswa ID #%d (%s, NISN %s) pindah dari kelas %s (%s) ke kelas %s (%s), tahun ajaran %s, tanggal %s. Alasan: %s. Penempatan lama #%d diakhiri dan penempatan baru #%d dibuat; status siswa tetap Aktif.',
                    $siswa->id,
                    $siswa->nama,
                    $siswa->nisn,
                    $previousClass->nama_kelas,
                    $previousClass->jurusan ?? 'tanpa jurusan',
                    $currentClass->nama_kelas,
                    $currentClass->jurusan ?? 'tanpa jurusan',
                    $currentPlacement->tahunAjaran->nama_tahun_ajaran,
                    $currentPlacement->tanggal_mulai->format('d/m/Y'),
                    $request->validated('alasan'),
                    $previousPlacement->id,
                    $currentPlacement->id,
                ),
            );
        });

        return to_route('admin.students.show', $siswa)->with('success', 'Siswa berhasil dipindahkan kelas. Status Aktif dan seluruh riwayat tetap tersimpan.');
    }

    /**
     * Memperbarui penempatan kelas siswa beserta riwayatnya.
     */
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

    /**
     * Menghapus penempatan siswa setelah aturan koreksi diperiksa.
     */
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
