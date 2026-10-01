<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaliKelas;

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
}
