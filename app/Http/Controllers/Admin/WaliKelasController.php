<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CloseWaliKelasRequest;
use App\Http\Requests\Admin\StoreWaliKelasRequest;
use App\Http\Requests\Admin\UpdateWaliKelasRequest;
use App\Http\Requests\Admin\WaliKelasIndexRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use App\Services\ActivityLogger;
use App\Services\WaliKelasAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Menangani permintaan HTTP untuk wali kelas, termasuk penyajian data dan perubahan yang diizinkan.
 */
class WaliKelasController extends Controller
{
    /**
     * Menampilkan daftar wali kelas dengan filter dan paginasi yang berlaku.
     */
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
            ->when(($filters['status'] ?? null) === 'aktif', static fn ($query) => $query->where('t_wali_kelas.status_aktif', true)->whereNull('t_wali_kelas.tanggal_selesai'))
            ->when(($filters['status'] ?? null) === 'selesai', static fn ($query) => $query->where(static function ($statusQuery): void {
                $statusQuery->where('t_wali_kelas.status_aktif', false)->orWhereNotNull('t_wali_kelas.tanggal_selesai');
            }))
            ->join('t_tahun_ajaran', 't_tahun_ajaran.id', '=', 't_wali_kelas.tahun_ajaran_id')
            ->select('t_wali_kelas.*')
            ->orderByDesc('t_tahun_ajaran.tanggal_mulai')
            ->orderByDesc('t_wali_kelas.tanggal_mulai')
            ->orderByDesc('t_wali_kelas.id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.wali-kelas.index', [
            'assignments' => $assignments,
            'filters' => $filters,
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(),
            'academicYears' => TahunAjaran::query()->orderByDesc('tanggal_mulai')->get(),
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat wali kelas.
     */
    public function create(): View
    {
        Gate::authorize('create', WaliKelas::class);

        return view('admin.wali-kelas.create', [
            'teachers' => Guru::query()->where('status_aktif', true)->orderBy('nama')->get(['id', 'nama', 'nip']),
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(),
            'academicYears' => TahunAjaran::query()->orderByDesc('tanggal_mulai')->get(),
        ]);
    }

    /**
     * Memvalidasi dan menyimpan wali kelas baru.
     */
    public function store(StoreWaliKelasRequest $request, WaliKelasAssignmentService $service, ActivityLogger $activityLogger): RedirectResponse
    {
        $assignment = $service->assign($request->validated());
        $activityLogger->record($request->user(), $request, 'Wali kelas ditugaskan', 'Penugasan wali kelas ID #'.$assignment->id.' berhasil dibuat.');

        return to_route('admin.homeroom-teachers.show', $assignment)->with('success', 'Penugasan wali kelas berhasil disimpan.');
    }

    /**
     * Menampilkan rincian wali kelas yang dipilih.
     */
    public function show(WaliKelas $waliKelas): View
    {
        Gate::authorize('view', $waliKelas);
        $waliKelas->load(['guru:id,nama,nip,status_aktif', 'kelas:id,nama_kelas,tingkat,jurusan', 'tahunAjaran:id,nama_tahun_ajaran,tanggal_mulai,tanggal_selesai']);

        return view('admin.wali-kelas.show', ['assignment' => $waliKelas]);
    }

    /**
     * Mengakhiri penugasan wali kelas pada tanggal yang divalidasi.
     */
    public function close(
        CloseWaliKelasRequest $request,
        WaliKelas $waliKelas,
        WaliKelasAssignmentService $service,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        $service->close($waliKelas, $request->validated('tanggal_selesai'));
        $activityLogger->record($request->user(), $request, 'Penugasan wali kelas diakhiri', 'Penugasan wali kelas ID #'.$waliKelas->id.' berhasil diakhiri.');

        return to_route('admin.homeroom-teachers.show', $waliKelas)->with('success', 'Penugasan wali kelas diakhiri; riwayat tetap tersimpan.');
    }

    /**
     * Mengubah tanggal penugasan setelah benturan periode diperiksa.
     */
    public function updateDates(
        UpdateWaliKelasRequest $request,
        WaliKelas $waliKelas,
        WaliKelasAssignmentService $service,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        DB::transaction(function () use ($request, $waliKelas, $service, $activityLogger): void {
            ['before' => $before, 'assignment' => $updatedAssignment] = $service->updateDates($waliKelas, $request->validated());
            $activityLogger->record(
                $request->user(),
                $request,
                'Tanggal penugasan wali kelas dikoreksi',
                'Penugasan ID #'.$updatedAssignment->id
                    .' (guru #'.$updatedAssignment->guru_id
                    .', kelas #'.$updatedAssignment->kelas_id
                    .', tahun ajaran #'.$updatedAssignment->tahun_ajaran_id
                    .') dikoreksi. Sebelum: '.json_encode($before, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
                    .'. Sesudah: '.json_encode([
                        'tanggal_mulai' => $updatedAssignment->tanggal_mulai->toDateString(),
                        'tanggal_selesai' => $updatedAssignment->tanggal_selesai?->toDateString(),
                        'status_aktif' => (bool) $updatedAssignment->status_aktif,
                    ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE).'.',
            );
        });

        return to_route('admin.homeroom-teachers.show', $waliKelas)->with('success', 'Tanggal penugasan wali kelas berhasil dikoreksi.');
    }
}
