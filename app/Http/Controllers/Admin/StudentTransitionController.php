<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcessStudentTransitionRequest;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\TahunAjaran;
use App\Services\StudentTransitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentTransitionController extends Controller
{
    public function show(Request $request, TahunAjaran $tahunAjaran, StudentTransitionService $transitionService): View
    {
        Gate::authorize('view', $tahunAjaran);

        $filters = $request->validate([
            'mode' => ['nullable', Rule::in(['naik', 'tinggal', 'lulus'])],
            'tahun_ajaran_tujuan_id' => ['nullable', 'integer', 'exists:t_tahun_ajaran,id'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $mode = $filters['mode'] ?? StudentTransitionService::MODE_PROMOTE;
        $targetYear = $mode === StudentTransitionService::MODE_GRADUATE || empty($filters['tahun_ajaran_tujuan_id'])
            ? null
            : TahunAjaran::query()->findOrFail($filters['tahun_ajaran_tujuan_id']);
        $preview = $mode === StudentTransitionService::MODE_GRADUATE || $targetYear !== null
            ? $transitionService->preview($tahunAjaran, $targetYear, $mode)
            : null;

        $destinationYears = TahunAjaran::query()
            ->whereKeyNot($tahunAjaran->id)
            ->whereDate('tanggal_mulai', '>', $tahunAjaran->tanggal_selesai->toDateString())
            ->orderBy('tanggal_mulai')
            ->get(['id', 'nama_tahun_ajaran', 'tanggal_mulai', 'tanggal_selesai']);
        $classes = Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas', 'tingkat', 'jurusan']);
        $history = LogAktivitas::query()
            ->with('pengguna:id,nama')
            ->whereIn('aktivitas', [
                'Kenaikan kelas massal',
                'Proses tinggal kelas massal',
                'Kelulusan massal',
            ])
            ->where('deskripsi', 'like', '%Tahun asal ID #'.$tahunAjaran->id.' ('.$tahunAjaran->nama_tahun_ajaran.')%')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
        $sourceYearHasEnded = $tahunAjaran->tanggal_selesai->lte(today());
        $eligibleCount = ($preview['items'] ?? collect())->where('eligible', true)->count();

        return view('admin.tahun-ajaran.student-transitions', compact(
            'tahunAjaran',
            'mode',
            'targetYear',
            'destinationYears',
            'classes',
            'preview',
            'history',
            'sourceYearHasEnded',
            'eligibleCount',
        ));
    }

    public function store(
        ProcessStudentTransitionRequest $request,
        TahunAjaran $tahunAjaran,
        StudentTransitionService $transitionService,
    ): RedirectResponse {
        $data = $request->validated();
        $mode = $data['mode'];
        $targetYear = $mode === StudentTransitionService::MODE_GRADUATE
            ? null
            : TahunAjaran::query()->findOrFail($data['tahun_ajaran_tujuan_id']);

        $result = $transitionService->process(
            $tahunAjaran,
            $targetYear,
            $mode,
            $data['pemetaan'] ?? [],
            $data['dikecualikan'] ?? [],
            $request->user(),
            $request,
        );

        $modeLabel = match ($mode) {
            StudentTransitionService::MODE_PROMOTE => 'kenaikan kelas',
            StudentTransitionService::MODE_REPEAT => 'tinggal kelas',
            StudentTransitionService::MODE_GRADUATE => 'kelulusan',
        };
        $message = sprintf(
            'Proses %s berhasil untuk %d siswa; %d siswa dikecualikan. ID batch: %s.',
            $modeLabel,
            $result['processed'],
            $result['excluded'],
            $result['batch_id'],
        );

        return to_route('admin.academic-years.student-transitions', [
            'tahunAjaran' => $tahunAjaran,
            'mode' => $mode,
            'tahun_ajaran_tujuan_id' => $targetYear?->id,
        ])->with('success', $message);
    }
}
