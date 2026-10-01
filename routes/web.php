<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Auth::user() instanceof User
    ? to_route(Auth::user()->dashboardRouteName())
    : to_route('login'));

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'account.active'])->group(function (): void {
    Route::get('/ui-demo', function (Request $request) {
        $user = $request->user();

        return view('demo.foundation', [
            'layout' => $user->role === 'admin' ? 'layouts.admin' : 'layouts.guru',
            'hasActiveWaliKelas' => false,
        ]);
    })->name('ui-demo');

    require __DIR__.'/admin.php';
    require __DIR__.'/guru.php';
});
