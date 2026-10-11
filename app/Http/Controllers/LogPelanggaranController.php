<?php

namespace App\Http\Controllers;

use App\Models\LogPelanggaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogPelanggaranController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate(
            ['q' => ['nullable', 'string', 'max:100']],
            [
                'q.string' => 'Pencarian harus berupa teks.',
                'q.max' => 'Pencarian maksimal 100 karakter.',
            ],
        );
        $search = trim($validated['q'] ?? '');
        $logs = LogPelanggaran::query()
            ->with([
                'pengguna:id,nama',
                'catatanPelanggaran:id,kelas_siswa_id,pelanggaran_id,tanggal_pelanggaran',
                'catatanPelanggaran.penempatanKelas:id,siswa_id',
                'catatanPelanggaran.penempatanKelas.siswa:id,nisn,nama',
                'catatanPelanggaran.pelanggaran:id,nama_pelanggaran',
            ])
            ->when($search !== '', static function ($query) use ($search): void {
                $term = addcslashes($search, '%_\\');
                $query->whereHas('pengguna', static fn ($userQuery) => $userQuery->where('nama', 'like', '%'.$term.'%'))
                    ->orWhereHas('catatanPelanggaran.penempatanKelas.siswa', static fn ($studentQuery) => $studentQuery
                        ->where('nama', 'like', '%'.$term.'%')
                        ->orWhere('nisn', 'like', '%'.$term.'%'));
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.log-pelanggaran.index', compact('logs', 'search'));
    }
}
