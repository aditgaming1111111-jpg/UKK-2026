<?php

namespace App\Policies;

use App\Models\TahunAjaran;
use App\Models\User;

class TahunAjaranPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, TahunAjaran $tahunAjaran): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, TahunAjaran $tahunAjaran): bool
    {
        return $user->role === 'admin';
    }
}
