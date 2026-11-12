<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

/**
 * Menentukan izin pengguna untuk kelas.
 */
class KelasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Kelas $kelas): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Kelas $kelas): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->role === 'admin';
    }
}
