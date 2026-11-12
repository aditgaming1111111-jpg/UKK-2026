<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Arahkan pengguna masuk ke dasbor sesuai perannya, atau ke halaman login.
Route::get('/', fn () => Auth::user() instanceof User
    ? to_route(Auth::user()->dashboardRouteName())
    : to_route('login'));

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Profil dan rute aplikasi internal memerlukan akun yang sudah aktif.
Route::middleware(['auth', 'account.active'])->group(function (): void {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Halaman demonstrasi memakai tata letak sesuai peran pengguna saat ini.
    Route::get('/ui-demo', function (Request $request) {
        $user = $request->user();

        return view('demo.foundation', [
            'layout' => $user->role === 'admin' ? 'layouts.admin' : 'layouts.guru',
            'hasActiveWaliKelas' => false,
        ]);
    })->name('ui-demo');

    // Pisahkan definisi rute berdasarkan peran agar mudah dikelola.
    require __DIR__.'/admin.php';
    require __DIR__.'/guru.php';
});
