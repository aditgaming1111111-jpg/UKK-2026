<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PelanggaranKategoriIndexRequest;
use App\Http\Requests\Admin\StorePelanggaranKategoriRequest;
use App\Http\Requests\Admin\UpdatePelanggaranKategoriRequest;
use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Menangani permintaan HTTP untuk kategori pelanggaran, termasuk penyajian data dan perubahan yang diizinkan.
 */
class PelanggaranKategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori pelanggaran dengan filter dan paginasi yang berlaku.
     */
    public function index(PelanggaranKategoriIndexRequest $request): View
    {
        $filters = $request->validated();
        $categories = PelanggaranKategori::query()
            ->withCount('jenisPelanggaran')
            ->when($filters['q'] ?? null, static fn($query, string $search) => $query->where('nama_kategori', 'like', '%' . $search . '%'))
            ->when(isset($filters['status']), static fn($query) => $query->where('status_aktif', $filters['status'] === 'aktif'))
            ->orderBy('nama_kategori')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori-pelanggaran.index', compact('categories', 'filters'));
    }

    /**
     * Menampilkan formulir untuk membuat kategori pelanggaran.
     */
    public function create(): View
    {
        Gate::authorize('create', PelanggaranKategori::class);

        return view('admin.kategori-pelanggaran.create');
    }

    /**
     * Memvalidasi dan menyimpan kategori pelanggaran baru.
     */
    public function store(StorePelanggaranKategoriRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $category = PelanggaranKategori::query()->create($request->validated());
        $activityLogger->record($request->user(), $request, 'Kategori pelanggaran ditambahkan', 'Kategori pelanggaran ID #' . $category->id . ' berhasil ditambahkan.');

        return to_route('admin.violation-categories.show', $category)->with('success', 'Kategori pelanggaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan rincian kategori pelanggaran yang dipilih.
     */
    public function show(PelanggaranKategori $pelanggaranKategori): View
    {
        Gate::authorize('view', $pelanggaranKategori);
        $pelanggaranKategori->loadCount(['jenisPelanggaran', 'jenisPelanggaran as active_violations_count' => static fn($query) => $query->where('status_aktif', true)]);
        $violations = $pelanggaranKategori->jenisPelanggaran()->withCount('catatanSiswa')->orderBy('nama_pelanggaran')->paginate(10);

        return view('admin.kategori-pelanggaran.show', ['category' => $pelanggaranKategori, 'violations' => $violations]);
    }

    /**
     * Menyiapkan formulir perubahan untuk kategori pelanggaran yang dipilih.
     */
    public function edit(PelanggaranKategori $pelanggaranKategori): View
    {
        Gate::authorize('update', $pelanggaranKategori);

        return view('admin.kategori-pelanggaran.edit', ['category' => $pelanggaranKategori]);
    }

    /**
     * Memvalidasi dan menyimpan perubahan pada kategori pelanggaran.
     */
    public function update(UpdatePelanggaranKategoriRequest $request, PelanggaranKategori $pelanggaranKategori, ActivityLogger $activityLogger): RedirectResponse
    {
        $pelanggaranKategori->update($request->validated());
        $activityLogger->record($request->user(), $request, 'Kategori pelanggaran diperbarui', 'Kategori pelanggaran ID #' . $pelanggaranKategori->id . ' berhasil diperbarui.');

        return to_route('admin.violation-categories.show', $pelanggaranKategori)->with('success', 'Kategori pelanggaran berhasil diperbarui.');
    }

    /**
     * Menghapus kategori pelanggaran setelah pemeriksaan otorisasi dan aturan terkait.
     */
    public function destroy(PelanggaranKategori $pelanggaranKategori, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('delete', $pelanggaranKategori);

        DB::transaction(static function () use ($pelanggaranKategori): void {
            $lockedCategory = PelanggaranKategori::query()->whereKey($pelanggaranKategori->id)->lockForUpdate()->firstOrFail();

            if ($lockedCategory->jenisPelanggaran()->exists()) {
                throw ValidationException::withMessages([
                    'pelanggaranKategori' => 'Kategori masih digunakan oleh jenis pelanggaran. Nonaktifkan kategori agar histori tetap terjaga.',
                ]);
            }

            $lockedCategory->delete();
        });
        $activityLogger->record($request->user(), $request, 'Kategori pelanggaran dihapus', 'Kategori pelanggaran ID #' . $pelanggaranKategori->id . ' tanpa jenis terkait berhasil dihapus.');

        return to_route('admin.violation-categories.index')->with('success', 'Kategori tanpa jenis pelanggaran berhasil dihapus permanen.');
    }

    /**
     * Toggle the active status of the category.
     */
    public function toggleStatus(PelanggaranKategori $pelanggaranKategori, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('update', $pelanggaranKategori);

        DB::transaction(function () use ($pelanggaranKategori): void {
            $category = PelanggaranKategori::query()->whereKey($pelanggaranKategori->id)->lockForUpdate()->firstOrFail();
            $isActivating = ! $category->status_aktif;
            $category->update(['status_aktif' => $isActivating]);

            if (! $isActivating) {
                Pelanggaran::query()->where('kategori_id', $category->id)->where('status_aktif', true)->update(['status_aktif' => false]);
            }
        });
        $activityLogger->record($request->user(), $request, 'Status kategori pelanggaran diubah', 'Status kategori pelanggaran ID #' . $pelanggaranKategori->id . ' diubah menjadi ' . ($pelanggaranKategori->fresh()->status_aktif ? 'aktif' : 'nonaktif') . '.');

        return to_route('admin.violation-categories.show', $pelanggaranKategori)->with('success', $pelanggaranKategori->fresh()->status_aktif
            ? 'Kategori diaktifkan kembali. Jenis pelanggaran tetap berstatus masing-masing.'
            : 'Kategori dinonaktifkan. Jenis di dalamnya juga dinonaktifkan; riwayat tetap tersimpan.');
    }
}
