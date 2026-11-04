<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogAktivitasController extends Controller
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

        $logs = LogAktivitas::query()
            ->with('pengguna:id,nama,role')
            ->when($search !== '', static function ($query) use ($search): void {
                $term = addcslashes($search, '%_\\');
                $query->where(static function ($logQuery) use ($term): void {
                    $logQuery->where('aktivitas', 'like', '%'.$term.'%')
                        ->orWhere('deskripsi', 'like', '%'.$term.'%')
                        ->orWhere('ip_address', 'like', '%'.$term.'%')
                        ->orWhereHas('pengguna', static fn ($userQuery) => $userQuery
                            ->where('nama', 'like', '%'.$term.'%')
                            ->orWhere('role', 'like', '%'.$term.'%'));
                });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.log-pelanggaran.index', compact('logs', 'search'));
    }
}
