<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaliKelas;

/**
 * Menentukan izin pengguna untuk wali kelas.
 */
class WaliKelasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, WaliKelas $waliKelas): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function close(User $user, WaliKelas $waliKelas): bool
    {
        return $user->role === 'admin';
    }

    public function updateDates(User $user, WaliKelas $waliKelas): bool
    {
        return $user->role === 'admin';
    }
}
