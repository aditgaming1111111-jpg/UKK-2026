<?php

namespace App\Policies;

use App\Models\Guru;
use App\Models\User;

class GuruPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Guru $guru): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Guru $guru): bool
    {
        return $user->role === 'admin';
    }

    public function manageAccount(User $user, Guru $guru): bool
    {
        return $user->role === 'admin';
    }

    public function changeStatus(User $user, Guru $guru): bool
    {
        return $user->role === 'admin';
    }
}
