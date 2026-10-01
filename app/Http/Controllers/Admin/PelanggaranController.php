<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PelanggaranIndexRequest;
use App\Http\Requests\Admin\StorePelanggaranRequest;
use App\Http\Requests\Admin\UpdatePelanggaranRequest;
use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PelanggaranIndexRequest $request): View
    {
        $filters = $request->validated();
        $violations = Pelanggaran::query()
            ->with('kategori:id,nama_kategori,status_aktif')
            ->withCount('catatanSiswa')
            ->when($filters['q'] ?? null, static fn ($query, string $search) => $query->where(static function ($searchQuery) use ($search): void {
                $searchQuery->where('kode', 'like', '%'.$search.'%')
                    ->orWhere('nama_pelanggaran', 'like', '%'.$search.'%');
            }))
            ->when($filters['kategori_id'] ?? null, static fn ($query, int|string $categoryId) => $query->where('kategori_id', $categoryId))
            ->when(isset($filters['status']), static fn ($query) => $query->where('status_aktif', $filters['status'] === 'aktif'))
            ->orderBy('kode')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.jenis-pelanggaran.index', [
            'violations' => $violations,
            'filters' => $filters,
            'categories' => PelanggaranKategori::query()->orderBy('nama_kategori')->get(['id', 'nama_kategori', 'status_aktif']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Pelanggaran::class);

        return view('admin.jenis-pelanggaran.create', [
            'categories' => PelanggaranKategori::query()->where('status_aktif', true)->orderBy('nama_kategori')->get(['id', 'nama_kategori']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePelanggaranRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $violation = DB::transaction(function () use ($attributes): Pelanggaran {
            $category = PelanggaranKategori::query()->whereKey($attributes['kategori_id'])->lockForUpdate()->firstOrFail();

            if (! $category->status_aktif) {
                throw ValidationException::withMessages(['kategori_id' => 'Kategori tidak aktif dan tidak dapat digunakan untuk jenis baru.']);
            }

            return $category->jenisPelanggaran()->create(collect($attributes)->except('kategori_id')->all());
        });

        return to_route('admin.violations.show', $violation)->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(UpdatePelanggaranRequest $request, Pelanggaran $pelanggaran): RedirectResponse
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

        return to_route('admin.violations.show', $pelanggaran)->with('success', 'Jenis pelanggaran berhasil diperbarui. Poin pada catatan lama tetap menggunakan snapshot saat pencatatan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function toggleStatus(Pelanggaran $pelanggaran): RedirectResponse
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

        return to_route('admin.violations.show', $pelanggaran)->with('success', $pelanggaran->fresh()->status_aktif
            ? 'Jenis pelanggaran diaktifkan.'
            : 'Jenis pelanggaran dinonaktifkan. Catatan dan poin historis tetap tersimpan.');
    }
}
