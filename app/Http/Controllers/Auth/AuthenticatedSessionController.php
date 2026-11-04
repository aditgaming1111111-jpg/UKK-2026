<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user instanceof User) {
            return to_route($user->dashboardRouteName());
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $email = Str::lower(trim($request->validated('email')));
        $rateLimitKey = sha1($email.'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        $authenticated = Auth::attemptWhen(
            [
                'email' => $email,
                'password' => $request->validated('password'),
            ],
            static fn (User $user): bool => $user->hasSupportedRole() && $user->isActiveAccount(),
        );

        if (! $authenticated) {
            RateLimiter::hit($rateLimitKey, 60);

            return back()->withErrors([
                'email' => 'Email atau kata sandi tidak cocok, atau akun tidak aktif.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($rateLimitKey);
        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();
        $activityLogger->record($user, $request, 'Login', 'Pengguna berhasil masuk sebagai '.$user->role.'.');

        return to_route($user->dashboardRouteName());
    }

    public function destroy(Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $activityLogger->record($user, $request, 'Logout', 'Pengguna keluar dari aplikasi sebagai '.$user->role.'.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login')->with('status', 'Anda berhasil keluar.');
    }
}
