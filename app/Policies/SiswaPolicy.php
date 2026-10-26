<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru'], true);
    }

    public function view(User $user, Siswa $siswa): bool
    {
        return in_array($user->role, ['admin', 'guru'], true);
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
