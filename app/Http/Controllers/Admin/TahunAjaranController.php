<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTahunAjaranRequest;
use App\Http\Requests\Admin\TahunAjaranIndexRequest;
use App\Http\Requests\Admin\UpdateTahunAjaranRequest;
use App\Models\TahunAjaran;
use App\Services\ActivityLogger;
use App\Services\TahunAjaranManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Menangani permintaan HTTP untuk tahun ajaran, termasuk penyajian data dan perubahan yang diizinkan.
 */
class TahunAjaranController extends Controller
{
    /**
     * Menampilkan daftar tahun ajaran dengan filter dan paginasi yang berlaku.
     */
    public function index(TahunAjaranIndexRequest $request): View
    {
        $filters = $request->validated();
        $academicYears = TahunAjaran::query()
            ->withCount(['penempatanSiswa', 'penugasanWaliKelas'])
            ->when($filters['q'] ?? null, static fn ($query, string $search) => $query
                ->where('nama_tahun_ajaran', 'like', '%'.$search.'%'))
            ->when(isset($filters['status']), static fn ($query) => $query->where('status_aktif', $filters['status'] === 'aktif'))
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tahun-ajaran.index', compact('academicYears', 'filters'));
    }

    /**
     * Menampilkan formulir untuk membuat tahun ajaran.
     */
    public function create(): View
    {
        Gate::authorize('create', TahunAjaran::class);

        return view('admin.tahun-ajaran.create');
    }

    /**
     * Memvalidasi dan menyimpan tahun ajaran baru.
     */
    public function store(StoreTahunAjaranRequest $request, TahunAjaranManager $manager, ActivityLogger $activityLogger): RedirectResponse
    {
        $academicYear = $manager->create($request->validated());
        $activityLogger->record($request->user(), $request, 'Tahun ajaran ditambahkan', 'Periode tahun ajaran ID #'.$academicYear->id.' berhasil ditambahkan.');

        return to_route('admin.academic-years.show', $academicYear)->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan rincian tahun ajaran yang dipilih.
     */
    public function show(TahunAjaran $tahunAjaran): View
    {
        Gate::authorize('view', $tahunAjaran);
        $tahunAjaran->loadCount(['penempatanSiswa', 'penugasanWaliKelas']);

        return view('admin.tahun-ajaran.show', ['academicYear' => $tahunAjaran]);
    }

    /**
     * Menyiapkan formulir perubahan untuk tahun ajaran yang dipilih.
     */
    public function edit(TahunAjaran $tahunAjaran): View
    {
        Gate::authorize('update', $tahunAjaran);

        return view('admin.tahun-ajaran.edit', ['academicYear' => $tahunAjaran]);
    }

    /**
     * Memvalidasi dan menyimpan perubahan pada tahun ajaran.
     */
    public function update(
        UpdateTahunAjaranRequest $request,
        TahunAjaran $tahunAjaran,
        TahunAjaranManager $manager,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        $manager->update($tahunAjaran, $request->validated());
        $activityLogger->record($request->user(), $request, 'Tahun ajaran diperbarui', 'Pengaturan tahun ajaran ID #'.$tahunAjaran->id.' berhasil diperbarui.');

        return to_route('admin.academic-years.show', $tahunAjaran)->with('success', 'Pengaturan tahun ajaran berhasil diperbarui.');
    }

    /**
     * Menghapus tahun ajaran setelah pemeriksaan otorisasi dan aturan terkait.
     */
    public function destroy(TahunAjaran $tahunAjaran, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('delete', $tahunAjaran);

        DB::transaction(static function () use ($tahunAjaran): void {
            $lockedYear = TahunAjaran::query()->whereKey($tahunAjaran->id)->lockForUpdate()->firstOrFail();

            if ($lockedYear->penempatanSiswa()->exists() || $lockedYear->penugasanWaliKelas()->exists()) {
                throw ValidationException::withMessages([
                    'tahun_ajaran' => 'Tahun ajaran sudah memiliki riwayat penempatan siswa atau penugasan wali kelas sehingga tidak dapat dihapus.',
                ]);
            }

            $lockedYear->delete();
        });
        $activityLogger->record($request->user(), $request, 'Tahun ajaran dihapus', 'Tahun ajaran ID #'.$tahunAjaran->id.' tanpa histori berhasil dihapus.');

        return to_route('admin.academic-years.index')->with('success', 'Tahun ajaran tanpa riwayat berhasil dihapus permanen.');
    }
}
