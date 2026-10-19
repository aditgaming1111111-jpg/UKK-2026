<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTahunAjaranRequest;
use App\Http\Requests\Admin\TahunAjaranIndexRequest;
use App\Http\Requests\Admin\UpdateTahunAjaranRequest;
use App\Models\TahunAjaran;
use App\Services\TahunAjaranManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', TahunAjaran::class);

        return view('admin.tahun-ajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTahunAjaranRequest $request, TahunAjaranManager $manager): RedirectResponse
    {
        $academicYear = $manager->create($request->validated());

        return to_route('admin.academic-years.show', $academicYear)->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAjaran $tahunAjaran): View
    {
        Gate::authorize('view', $tahunAjaran);
        $tahunAjaran->loadCount(['penempatanSiswa', 'penugasanWaliKelas']);

        return view('admin.tahun-ajaran.show', ['academicYear' => $tahunAjaran]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran): View
    {
        Gate::authorize('update', $tahunAjaran);

        return view('admin.tahun-ajaran.edit', ['academicYear' => $tahunAjaran]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateTahunAjaranRequest $request,
        TahunAjaran $tahunAjaran,
        TahunAjaranManager $manager,
    ): RedirectResponse {
        $manager->update($tahunAjaran, $request->validated());

        return to_route('admin.academic-years.show', $tahunAjaran)->with('success', 'Pengaturan tahun ajaran berhasil diperbarui.');
    }
}
