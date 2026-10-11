<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->load('guru:id,user_id,nip,nama,email,status_aktif');
        $activeYearId = TahunAjaran::query()->where('status_aktif', true)->value('id');
        $hasActiveWaliKelas = $user->role === 'guru'
            && $activeYearId !== null
            && WaliKelas::query()
                ->where('guru_id', $user->guru?->id)
                ->where('tahun_ajaran_id', $activeYearId)
                ->where('status_aktif', true)
                ->exists();

        return view('profile.edit', [
            'user' => $user,
            'hasActiveWaliKelas' => $hasActiveWaliKelas,
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $attributes = $request->safe()->only(['nama', 'email']);
        $userId = $request->user()->id;

        DB::transaction(static function () use ($attributes, $userId): void {
            $user = User::query()->whereKey($userId)->lockForUpdate()->firstOrFail();
            $user->update($attributes);

            if ($user->role === 'guru') {
                Guru::query()->where('user_id', $user->id)->lockForUpdate()->firstOrFail()
                    ->update(['nama' => $attributes['nama']]);
            }
        });

        return to_route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update(['password' => $request->validated('password')]);
        $request->session()->regenerate();

        return to_route('profile.edit')->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
