<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatatanSayaIndexRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PelanggaranSiswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CatatanSayaController extends Controller
{
    public function index(CatatanSayaIndexRequest $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $teacher = $this->activeTeacherFor($user);
        $filters = $request->validated();
        $recordsQuery = PelanggaranSiswa::query()
            ->whereBelongsTo($teacher, 'guru')
            ->with([
                'penempatanKelas.siswa:id,nisn,nama',
                'penempatanKelas.kelas:id,nama_kelas,tingkat',
                'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
                'pelanggaran:id,kode,nama_pelanggaran',
            ]);

        if (($filters['q'] ?? '') !== '') {
            $term = addcslashes(trim($filters['q']), '%_\\');
            $recordsQuery->whereHas('penempatanKelas.siswa', static fn (Builder $studentQuery) => $studentQuery
                ->where('nama', 'like', '%'.$term.'%')
                ->orWhere('nisn', 'like', '%'.$term.'%'));
        }

        $records = $recordsQuery
            ->when($filters['tahun_ajaran_id'] ?? null, static fn (Builder $query, int|string $yearId) => $query
                ->whereHas('penempatanKelas', static fn (Builder $placementQuery) => $placementQuery->where('tahun_ajaran_id', $yearId)))
            ->when($filters['kelas_id'] ?? null, static fn (Builder $query, int|string $classId) => $query
                ->whereHas('penempatanKelas', static fn (Builder $placementQuery) => $placementQuery->where('kelas_id', $classId)))
            ->when($filters['tanggal_mulai'] ?? null, static fn (Builder $query, string $date) => $query->whereDate('tanggal_pelanggaran', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, static fn (Builder $query, string $date) => $query->whereDate('tanggal_pelanggaran', '<=', $date))
            ->when($filters['status'] ?? null, static fn (Builder $query, string $status) => $query->where('status', $status))
            ->orderByDesc('tanggal_pelanggaran')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('guru.catatan-saya.index', [
            'records' => $records,
            'filters' => $filters,
            'academicYears' => TahunAjaran::query()
                ->whereHas('penempatanSiswa.catatanPelanggaran', static fn (Builder $query) => $query->where('guru_id', $teacher->id))
                ->orderByDesc('tanggal_mulai')
                ->get(['id', 'nama_tahun_ajaran']),
            'classes' => Kelas::query()
                ->whereHas('penempatanSiswa.catatanPelanggaran', static fn (Builder $query) => $query->where('guru_id', $teacher->id))
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get(['id', 'nama_kelas', 'tingkat']),
        ]);
    }

    public function show(PelanggaranSiswa $pelanggaranSiswa): View
    {
        Gate::authorize('viewOwn', $pelanggaranSiswa);
        $pelanggaranSiswa->load([
            'penempatanKelas.siswa:id,nisn,nama',
            'penempatanKelas.kelas:id,nama_kelas,tingkat,jurusan',
            'penempatanKelas.tahunAjaran:id,nama_tahun_ajaran',
            'pelanggaran:id,kategori_id,kode,nama_pelanggaran,status_aktif',
            'pelanggaran.kategori:id,nama_kategori,status_aktif',
            'guru:id,nama',
        ]);
        $pelanggaranSiswa->setRelation('logPerubahan', $pelanggaranSiswa->logPerubahan()
            ->with('pengguna:id,nama')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get());

        return view('guru.catatan-saya.show', [
            'record' => $pelanggaranSiswa,
            'cancellationLog' => $pelanggaranSiswa->logPerubahan->firstWhere('aksi', 'batal'),
            'routePrefix' => 'guru.my-notes',
        ]);
    }

    private function activeTeacherFor(User $user): Guru
    {
        return $user->guru()->where('status_aktif', true)->firstOrFail();
    }
}
