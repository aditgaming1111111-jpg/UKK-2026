<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CloseWaliKelasRequest;
use App\Http\Requests\Admin\StoreWaliKelasRequest;
use App\Http\Requests\Admin\WaliKelasIndexRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use App\Services\WaliKelasAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class WaliKelasController extends Controller
{
    public function index(WaliKelasIndexRequest $request): View
    {
        $filters = $request->validated();
        $assignments = WaliKelas::query()
            ->with(['guru:id,nama,nip,status_aktif', 'kelas:id,nama_kelas,tingkat,jurusan', 'tahunAjaran:id,nama_tahun_ajaran'])
            ->when($filters['q'] ?? null, static fn ($query, string $search) => $query->where(static function ($searchQuery) use ($search): void {
                $searchQuery->whereHas('guru', static fn ($guruQuery) => $guruQuery
                    ->where('nama', 'like', '%'.$search.'%')->orWhere('nip', 'like', '%'.$search.'%'))
                    ->orWhereHas('kelas', static fn ($classQuery) => $classQuery->where('nama_kelas', 'like', '%'.$search.'%'));
            }))
            ->when($filters['kelas_id'] ?? null, static fn ($query, int|string $classId) => $query->where('kelas_id', $classId))
            ->when($filters['tahun_ajaran_id'] ?? null, static fn ($query, int|string $yearId) => $query->where('tahun_ajaran_id', $yearId))
            ->when(($filters['status'] ?? null) === 'aktif', static fn ($query) => $query->where('status_aktif', true)->whereNull('tanggal_selesai'))
            ->when(($filters['status'] ?? null) === 'selesai', static fn ($query) => $query->where(static function ($statusQuery): void {
                $statusQuery->where('status_aktif', false)->orWhereNotNull('tanggal_selesai');
            }))
            ->join('t_tahun_ajaran', 't_tahun_ajaran.id', '=', 't_wali_kelas.tahun_ajaran_id')
            ->select('t_wali_kelas.*')
            ->orderByDesc('t_tahun_ajaran.tanggal_mulai')
            ->orderByDesc('t_wali_kelas.tanggal_mulai')
            ->orderByDesc('t_wali_kelas.id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.wali-kelas.index', [
            'assignments' => $assignments,
            'filters' => $filters,
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(),
            'academicYears' => TahunAjaran::query()->orderByDesc('tanggal_mulai')->get(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', WaliKelas::class);

        return view('admin.wali-kelas.create', [
            'teachers' => Guru::query()->where('status_aktif', true)->orderBy('nama')->get(['id', 'nama', 'nip']),
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(),
            'academicYears' => TahunAjaran::query()->orderByDesc('tanggal_mulai')->get(),
        ]);
    }

    public function store(StoreWaliKelasRequest $request, WaliKelasAssignmentService $service): RedirectResponse
    {
        $assignment = $service->assign($request->validated());

        return to_route('admin.homeroom-teachers.show', $assignment)->with('success', 'Penugasan wali kelas berhasil disimpan.');
    }

    public function show(WaliKelas $waliKelas): View
    {
        Gate::authorize('view', $waliKelas);
        $waliKelas->load(['guru:id,nama,nip,status_aktif', 'kelas:id,nama_kelas,tingkat,jurusan', 'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai']);

        return view('admin.wali-kelas.show', ['assignment' => $waliKelas]);
    }

    public function close(
        CloseWaliKelasRequest $request,
        WaliKelas $waliKelas,
        WaliKelasAssignmentService $service,
    ): RedirectResponse {
        $service->close($waliKelas, $request->validated('tanggal_selesai'));

        return to_route('admin.homeroom-teachers.show', $waliKelas)->with('success', 'Penugasan wali kelas diakhiri; riwayat tetap tersimpan.');
    }
}
