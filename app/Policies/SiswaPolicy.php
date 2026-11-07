<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class SiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru'], true);
    }

    public function view(User $user, Siswa $siswa): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role !== 'guru') {
            return false;
        }

        if ($siswa->status_siswa !== 'aktif') {
            return false;
        }

        $today = today()->toDateString();

        return $user->guru()
            ->where('status_aktif', true)
            ->whereHas('waliKelas', static function (Builder $assignmentQuery) use ($siswa, $today): void {
                $assignmentQuery
                    ->where('status_aktif', true)
                    ->whereDate('tanggal_mulai', '<=', $today)
                    ->where(static function (Builder $query) use ($today): void {
                        $query->whereNull('tanggal_selesai')
                            ->orWhereDate('tanggal_selesai', '>=', $today);
                    })
                    ->whereHas('tahunAjaran', static fn (Builder $yearQuery): Builder => $yearQuery
                        ->whereDate('tanggal_mulai', '<=', $today)
                        ->whereDate('tanggal_selesai', '>=', $today))
                    ->whereExists(static function (QueryBuilder $placementQuery) use ($siswa, $today): void {
                        $placementQuery->selectRaw('1')
                            ->from('t_kelas_siswa')
                            ->where('t_kelas_siswa.siswa_id', $siswa->id)
                            ->whereColumn('t_kelas_siswa.kelas_id', 't_wali_kelas.kelas_id')
                            ->whereColumn('t_kelas_siswa.tahun_ajaran_id', 't_wali_kelas.tahun_ajaran_id')
                            ->where('t_kelas_siswa.status_aktif', true)
                            ->whereDate('t_kelas_siswa.tanggal_mulai', '<=', $today)
                            ->where(static function (QueryBuilder $query) use ($today): void {
                                $query->whereNull('t_kelas_siswa.tanggal_selesai')
                                    ->orWhereDate('t_kelas_siswa.tanggal_selesai', '>=', $today);
                            });
                    });
            })
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Siswa $siswa): bool
    {
        return $user->role === 'admin';
    }

    public function deactivate(User $user, Siswa $siswa): bool
    {
        return $user->role === 'admin';
    }

    public function changeStatus(User $user, Siswa $siswa): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->role === 'admin';
    }

    public function managePlacements(User $user, Siswa $siswa): bool
    {
        return $user->role === 'admin';
    }
}
