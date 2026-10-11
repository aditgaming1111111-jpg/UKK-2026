<?php

namespace App\Policies;

use App\Models\PelanggaranSiswa;
use App\Models\User;

class PelanggaranSiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru'], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PelanggaranSiswa $pelanggaranSiswa): bool
    {
        return PelanggaranSiswa::query()
            ->visibleTo($user)
            ->whereKey($pelanggaranSiswa->id)
            ->exists();
    }

    public function viewOwn(User $user, PelanggaranSiswa $pelanggaranSiswa): bool
    {
        return $user->role === 'guru'
            && $user->guru()->where('status_aktif', true)->whereKey($pelanggaranSiswa->guru_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'guru';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PelanggaranSiswa $pelanggaranSiswa): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'guru'
                && $user->guru()->where('status_aktif', true)->whereKey($pelanggaranSiswa->guru_id)->exists());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PelanggaranSiswa $pelanggaranSiswa): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PelanggaranSiswa $pelanggaranSiswa): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PelanggaranSiswa $pelanggaranSiswa): bool
    {
        return false;
    }

    public function cancel(User $user, PelanggaranSiswa $pelanggaranSiswa): bool
    {
        return $pelanggaranSiswa->status === 'aktif' && $this->update($user, $pelanggaranSiswa);
    }
}
