<?php

namespace App\Services;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function record(User $user, Request $request, string $activity, string $description): LogAktivitas
    {
        return LogAktivitas::query()->create([
            'user_id' => $user->id,
            'aktivitas' => $activity,
            'deskripsi' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
