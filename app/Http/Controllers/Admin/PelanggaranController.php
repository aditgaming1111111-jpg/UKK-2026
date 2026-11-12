<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PelanggaranIndexRequest;
use App\Http\Requests\Admin\StorePelanggaranRequest;
use App\Http\Requests\Admin\UpdatePelanggaranRequest;
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
 * Menangani permintaan HTTP untuk pelanggaran, termasuk penyajian data dan perubahan yang diizinkan.
 */
class PelanggaranController extends Controller
{
    /**
     * Menampilkan daftar jenis pelanggaran dengan filter dan paginasi yang berlaku.
     */
    public function index(PelanggaranIndexRequest $request): View
    {
        $filters = $request->validated();
        $violations = Pelanggaran::query()
            ->with('kategori:id,nama_kategori,status_aktif')
            ->withCount('catatanSiswa')
            ->when($filters['q'] ?? null, static fn($query, string $search) => $query->where(static function ($searchQuery) use ($search): void {
                $searchQuery->where('kode', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelanggaran', 'like', '%' . $search . '%');
            }))
            ->when($filters['kategori_id'] ?? null, static fn($query, int|string $categoryId) => $query->where('kategori_id', $categoryId))
            ->when(isset($filters['status']), static fn($query) => $query->where('status_aktif', $filters['status'] === 'aktif'))
            ->orderBy('kode')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jenis-pelanggaran.index', [
            'violations' => $violations,
            'filters' => $filters,
            'categories' => PelanggaranKategori::query()->orderBy('nama_kategori')->get(['id', 'nama_kategori', 'status_aktif']),
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat jenis pelanggaran.
     */
    public function create(): View
    {
        Gate::authorize('create', Pelanggaran::class);

        return view('admin.jenis-pelanggaran.create', [
            'categories' => PelanggaranKategori::query()->where('status_aktif', true)->orderBy('nama_kategori')->get(['id', 'nama_kategori']),
        ]);
    }

    /**
     * Memvalidasi dan menyimpan jenis pelanggaran baru.
     */
    public function store(StorePelanggaranRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $attributes = $request->validated();
        $violation = DB::transaction(function () use ($attributes): Pelanggaran {
            $category = PelanggaranKategori::query()->whereKey($attributes['kategori_id'])->lockForUpdate()->firstOrFail();

            if (! $category->status_aktif) {
                throw ValidationException::withMessages(['kategori_id' => 'Kategori tidak aktif dan tidak dapat digunakan untuk jenis baru.']);
            }

            return $category->jenisPelanggaran()->create(collect($attributes)->except('kategori_id')->all());
        });
        $activityLogger->record($request->user(), $request, 'Jenis pelanggaran ditambahkan', 'Jenis pelanggaran ID #' . $violation->id . ' berhasil ditambahkan.');

        return to_route('admin.violations.show', $violation)->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan rincian jenis pelanggaran yang dipilih.
     */
    public function show(Pelanggaran $pelanggaran): View
    {
        Gate::authorize('view', $pelanggaran);
        $pelanggaran->load('kategori:id,nama_kategori,status_aktif')->loadCount('catatanSiswa');
        $recentRecords = $pelanggaran->catatanSiswa()
            ->with(['penempatanKelas.siswa:id,nisn,nama', 'guru:id,nama'])
            ->latest('tanggal_pelanggaran')
            ->limit(10)
            ->get();

        return view('admin.jenis-pelanggaran.show', compact('pelanggaran', 'recentRecords'));
    }

    /**
     * Menyiapkan formulir perubahan untuk jenis pelanggaran yang dipilih.
     */
    public function edit(Pelanggaran $pelanggaran): View
    {
        Gate::authorize('update', $pelanggaran);

        return view('admin.jenis-pelanggaran.edit', [
            'violation' => $pelanggaran,
            'categories' => PelanggaranKategori::query()
                ->where('status_aktif', true)
                ->orWhereKey($pelanggaran->kategori_id)
                ->orderBy('nama_kategori')
                ->get(['id', 'nama_kategori', 'status_aktif']),
        ]);
    }

    /**
     * Memvalidasi dan menyimpan perubahan pada jenis pelanggaran.
     */
    public function update(UpdatePelanggaranRequest $request, Pelanggaran $pelanggaran, ActivityLogger $activityLogger): RedirectResponse
    {
        $attributes = $request->validated();

        DB::transaction(function () use ($pelanggaran, $attributes): void {
            $category = PelanggaranKategori::query()->whereKey($attributes['kategori_id'])->lockForUpdate()->firstOrFail();
            $lockedViolation = Pelanggaran::query()->whereKey($pelanggaran->id)->lockForUpdate()->firstOrFail();

            if ($attributes['status_aktif'] && ! $category->status_aktif) {
                throw ValidationException::withMessages(['kategori_id' => 'Jenis pelanggaran hanya dapat diaktifkan pada kategori yang aktif.']);
            }

            $lockedViolation->update($attributes);
        });
        $activityLogger->record($request->user(), $request, 'Jenis pelanggaran diperbarui', 'Data jenis pelanggaran ID #' . $pelanggaran->id . ' diperbarui. Snapshot poin pada catatan lama tidak berubah.');

        return to_route('admin.violations.show', $pelanggaran)->with('success', 'Jenis pelanggaran berhasil diperbarui. Poin pada catatan lama tetap menggunakan snapshot saat pencatatan.');
    }

    /**
     * Menghapus pelanggaran setelah pemeriksaan otorisasi dan aturan terkait.
     */
    public function destroy(Pelanggaran $pelanggaran, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('delete', $pelanggaran);

        DB::transaction(static function () use ($pelanggaran): void {
            $lockedViolation = Pelanggaran::query()->whereKey($pelanggaran->id)->lockForUpdate()->firstOrFail();

            if ($lockedViolation->catatanSiswa()->exists()) {
                throw ValidationException::withMessages([
                    'pelanggaran' => 'Jenis pelanggaran sudah digunakan pada catatan siswa. Nonaktifkan jenis ini agar histori dan snapshot poin tetap terjaga.',
                ]);
            }

            $lockedViolation->delete();
        });
        $activityLogger->record($request->user(), $request, 'Jenis pelanggaran dihapus', 'Jenis pelanggaran ID #' . $pelanggaran->id . ' tanpa catatan terkait berhasil dihapus.');

        return to_route('admin.violations.index')->with('success', 'Jenis pelanggaran tanpa catatan siswa berhasil dihapus permanen.');
    }

    /**
     * Toggle the active status of the violation type.
     */
    public function toggleStatus(Pelanggaran $pelanggaran, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('update', $pelanggaran);

        DB::transaction(function () use ($pelanggaran): void {
            $category = PelanggaranKategori::query()->whereKey($pelanggaran->kategori_id)->lockForUpdate()->firstOrFail();
            $lockedViolation = Pelanggaran::query()->whereKey($pelanggaran->id)->lockForUpdate()->firstOrFail();
            $isActivating = ! $lockedViolation->status_aktif;

            if ($isActivating && ! $category->status_aktif) {
                throw ValidationException::withMessages(['status_aktif' => 'Aktifkan kategori terlebih dahulu sebelum mengaktifkan jenis pelanggaran.']);
            }

            $lockedViolation->update(['status_aktif' => $isActivating]);
        });
        $activityLogger->record($request->user(), $request, 'Status jenis pelanggaran diubah', 'Status jenis pelanggaran ID #' . $pelanggaran->id . ' diubah menjadi ' . ($pelanggaran->fresh()->status_aktif ? 'aktif' : 'nonaktif') . '.');

        return to_route('admin.violations.show', $pelanggaran)->with('success', $pelanggaran->fresh()->status_aktif
            ? 'Jenis pelanggaran diaktifkan.'
            : 'Jenis pelanggaran dinonaktifkan. Catatan dan poin historis tetap tersimpan.');
    }
}
