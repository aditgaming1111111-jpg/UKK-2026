<?php

use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\SiswaController;
use Illuminate\Support\Facades\Route;

Route::prefix('guru')
    ->name('guru.')
    ->middleware('role:guru')
    ->group(function (): void {
        Route::get('/', GuruDashboardController::class)->name('dashboard');
        Route::get('siswa', [SiswaController::class, 'index'])->name('students.index');
        Route::get('siswa/{siswa}', [SiswaController::class, 'show'])->name('students.show');
    });
