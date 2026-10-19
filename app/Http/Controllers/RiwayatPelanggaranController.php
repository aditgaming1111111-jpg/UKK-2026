<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelPelanggaranSiswaRequest;
use App\Http\Requests\RiwayatPelanggaranIndexRequest;
use App\Http\Requests\UpdatePelanggaranSiswaRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\TahunAjaran;
use App\Services\RiwayatPelanggaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RiwayatPelanggaranController extends Controller
{
    public function index(RiwayatPelanggaranIndexRequest $request): View
    {
        $filters = $request->validated();
        $user = $request->user();
        $query = PelanggaranSiswa::query()
            ->visibleTo($user)
            ->with([
                'penempatanKelas.siswa:id,nisn,nama',
                'penempatanKelas.kelas:id,nama_kelas,tingkat,jurusan',
                'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
                'pelanggaran:id,kode,nama_pelanggaran',
                'guru:id,nama',
            ]);

        if (($filters['q'] ?? '') !== '') {
            $term = addcslashes(trim($filters['q']), '%_\\');
            $query->whereHas('penempatanKelas.siswa', static fn ($studentQuery) => $studentQuery
                ->where('nisn', 'like', '%'.$term.'%')
                ->orWhere('nama', 'like', '%'.$term.'%'));
        }

        $query
            ->when($filters['tahun_ajaran_id'] ?? null, static fn ($builder, int|string $id) => $builder
                ->whereHas('penempatanKelas', static fn ($placementQuery) => $placementQuery->where('tahun_ajaran_id', $id)))
            ->when($filters['kelas_id'] ?? null, static fn ($builder, int|string $id) => $builder
                ->whereHas('penempatanKelas', static fn ($placementQuery) => $placementQuery->where('kelas_id', $id)))
            ->when($filters['pelanggaran_id'] ?? null, static fn ($builder, int|string $id) => $builder->where('pelanggaran_id', $id))
            ->when($filters['guru_id'] ?? null, static fn ($builder, int|string $id) => $builder->where('guru_id', $id))
            ->when($filters['status'] ?? null, static fn ($builder, string $status) => $builder->where('status', $status))
            ->when($filters['tanggal_mulai'] ?? null, static fn ($builder, string $date) => $builder->whereDate('tanggal_pelanggaran', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, static fn ($builder, string $date) => $builder->whereDate('tanggal_pelanggaran', '<=', $date));

        $records = $query->orderByDesc('tanggal_pelanggaran')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('pelanggaran-siswa.index', [
            'records' => $records,
            'filters' => $filters,
            'academicYears' => TahunAjaran::query()->orderByDesc('tanggal_mulai')->get(['id', 'nama_tahun_ajaran']),
            'classes' => Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas', 'tingkat']),
            'violations' => Pelanggaran::query()->with('kategori:id,nama_kategori')->orderBy('nama_pelanggaran')->get(['id', 'kategori_id', 'kode', 'nama_pelanggaran', 'status_aktif']),
            'teachers' => Guru::query()->orderBy('nama')->get(['id', 'nama', 'status_aktif']),
            'role' => $user->role,
            'routePrefix' => $this->routePrefix($user->role),
        ]);
    }

    public function show(PelanggaranSiswa $pelanggaranSiswa, RiwayatPelanggaranService $service): View
    {
        Gate::authorize('view', $pelanggaranSiswa);
        $pelanggaranSiswa->load($service->detailRelations());
        $pelanggaranSiswa->setRelation('logPerubahan', $pelanggaranSiswa->logPerubahan()
            ->with('pengguna:id,nama')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get());
        $role = request()->user()->role;

        return view('pelanggaran-siswa.show', [
            'record' => $pelanggaranSiswa,
            'routePrefix' => $this->routePrefix($role),
            'canManage' => request()->user()->can('update', $pelanggaranSiswa),
        ]);
    }

    public function edit(PelanggaranSiswa $pelanggaranSiswa): View
    {
        Gate::authorize('update', $pelanggaranSiswa);
        $pelanggaranSiswa->load([
            'penempatanKelas.siswa:id,nisn,nama',
            'penempatanKelas.kelas:id,nama_kelas',
            'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
            'pelanggaran:id,kategori_id,kode,nama_pelanggaran,poin,status_aktif',
        ]);
        $violations = Pelanggaran::query()
            ->where(fn ($query) => $query->where('status_aktif', true)->whereHas('kategori', static fn ($categoryQuery) => $categoryQuery->where('status_aktif', true)))
            ->orWhereKey($pelanggaranSiswa->pelanggaran_id)
            ->with('kategori:id,nama_kategori')
            ->orderBy('nama_pelanggaran')
            ->get(['id', 'kategori_id', 'kode', 'nama_pelanggaran', 'poin', 'status_aktif']);

        return view('pelanggaran-siswa.edit', [
            'record' => $pelanggaranSiswa,
            'violations' => $violations,
            'routePrefix' => $this->routePrefix(request()->user()->role),
        ]);
    }

    public function update(
        UpdatePelanggaranSiswaRequest $request,
        PelanggaranSiswa $pelanggaranSiswa,
        RiwayatPelanggaranService $service,
    ): RedirectResponse {
        $record = $service->update($request->user(), $pelanggaranSiswa, $request->validated());

        return to_route($this->routePrefix($request->user()->role).'.show', $record)
            ->with('success', 'Catatan pelanggaran berhasil dikoreksi dan dicatat dalam log audit.');
    }

    public function cancel(
        CancelPelanggaranSiswaRequest $request,
        PelanggaranSiswa $pelanggaranSiswa,
        RiwayatPelanggaranService $service,
    ): RedirectResponse {
        $record = $service->cancel($request->user(), $pelanggaranSiswa, $request->validated('alasan'));

        return to_route($this->routePrefix($request->user()->role).'.show', $record)
            ->with('success', 'Catatan pelanggaran dibatalkan. Riwayat dan log audit tetap tersimpan.');
    }

    private function routePrefix(string $role): string
    {
        return $role === 'admin' ? 'admin.student-violations' : 'guru.violations';
    }
}
