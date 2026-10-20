<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasIndexRequest;
use App\Http\Requests\Admin\StoreKelasRequest;
use App\Http\Requests\Admin\UpdateKelasRequest;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(KelasIndexRequest $request): View
    {
        $filters = $request->validated();

        $classes = Kelas::query()
            ->withCount([
                'penempatanSiswa as active_placement_count' => static fn ($query) => $query
                    ->where('status_aktif', true)
                    ->whereNull('tanggal_selesai'),
                'penempatanSiswa as historical_placement_count',
            ])
            ->when($filters['q'] ?? null, static fn ($query, string $search) => $query->where(static function ($searchQuery) use ($search): void {
                $searchQuery->where('nama_kelas', 'like', '%'.$search.'%')
                    ->orWhere('tingkat', 'like', '%'.$search.'%')
                    ->orWhere('jurusan', 'like', '%'.$search.'%');
            }))
            ->when(($filters['status'] ?? null) === 'terisi', static fn ($query) => $query->whereHas('penempatanSiswa', static fn ($placementQuery) => $placementQuery
                ->where('status_aktif', true)->whereNull('tanggal_selesai')))
            ->when(($filters['status'] ?? null) === 'kosong', static fn ($query) => $query->whereDoesntHave('penempatanSiswa', static fn ($placementQuery) => $placementQuery
                ->where('status_aktif', true)->whereNull('tanggal_selesai')))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kelas.index', compact('classes', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Kelas::class);

        return view('admin.kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKelasRequest $request): RedirectResponse
    {
        $kelas = Kelas::query()->create($request->validated());

        return to_route('admin.classes.show', $kelas)->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Kelas $kelas): View
    {
        Gate::authorize('view', $kelas);
        $kelas->loadCount(['penempatanSiswa', 'penugasanWaliKelas', 'penempatanSiswa as active_placement_count' => static fn ($query) => $query
            ->where('status_aktif', true)->whereNull('tanggal_selesai')]);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $academicYear = TahunAjaran::query()->where('status_aktif', true)->orderByDesc('tanggal_mulai')->first();
        $placements = $academicYear === null
            ? null
            : KelasSiswa::query()
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $academicYear->id)
                ->where('status_aktif', true)
                ->whereDate('tanggal_mulai', '<=', today())
                ->whereNull('tanggal_selesai')
                ->whereHas('siswa', static fn (Builder $query): Builder => $query->where('status_siswa', 'aktif'))
                ->with('siswa:id,nisn,nama,status_siswa')
                ->when($filters['q'] ?? null, static fn (Builder $query, string $search): Builder => $query
                    ->whereHas('siswa', static fn (Builder $studentQuery): Builder => $studentQuery
                        ->where(static function (Builder $searchQuery) use ($search): void {
                            $searchQuery->where('nisn', 'like', '%'.$search.'%')
                                ->orWhere('nama', 'like', '%'.$search.'%');
                        })))
                ->orderBy('id')
                ->paginate(10)
                ->withQueryString();

        return view('admin.kelas.show', compact('kelas', 'academicYear', 'placements', 'filters'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas): View
    {
        Gate::authorize('update', $kelas);

        return view('admin.kelas.edit', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKelasRequest $request, Kelas $kelas): RedirectResponse
    {
        $kelas->update($request->validated());

        return to_route('admin.classes.show', $kelas)->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas): RedirectResponse
    {
        Gate::authorize('delete', $kelas);

        if (KelasSiswa::query()->where('kelas_id', $kelas->id)->exists()
            || WaliKelas::query()->where('kelas_id', $kelas->id)->exists()) {
            throw ValidationException::withMessages([
                'kelas' => 'Kelas sudah memiliki riwayat penempatan atau penugasan sehingga tidak dapat dihapus.',
            ]);
        }

        $kelas->delete();

        return to_route('admin.classes.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
