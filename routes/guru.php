<?php

use App\Http\Controllers\Guru\CatatanSayaController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\PelanggaranSiswaController;
use App\Http\Controllers\Guru\RekapKelasController;
use App\Http\Controllers\Guru\SiswaController;
use App\Http\Controllers\RiwayatPelanggaranController;
use Illuminate\Support\Facades\Route;

Route::prefix('guru')
    ->name('guru.')
    ->middleware('role:guru')
    ->group(function (): void {
        Route::get('/', GuruDashboardController::class)->name('dashboard');
        Route::get('rekap-kelas', [RekapKelasController::class, 'index'])->name('class-recap.index');
        Route::get('pelanggaran/create', [PelanggaranSiswaController::class, 'create'])->name('violations.create');
        Route::get('pelanggaran/cari-siswa', [PelanggaranSiswaController::class, 'searchStudents'])->name('violations.student-search');
        Route::post('pelanggaran', [PelanggaranSiswaController::class, 'store'])->name('violations.store');
        Route::get('pelanggaran/riwayat', [RiwayatPelanggaranController::class, 'index'])->name('violations.index');
        Route::get('pelanggaran/riwayat/{pelanggaranSiswa}', [RiwayatPelanggaranController::class, 'show'])->name('violations.show');
        Route::get('pelanggaran/riwayat/{pelanggaranSiswa}/edit', [RiwayatPelanggaranController::class, 'edit'])->name('violations.edit');
        Route::put('pelanggaran/riwayat/{pelanggaranSiswa}', [RiwayatPelanggaranController::class, 'update'])->name('violations.update');
        Route::patch('pelanggaran/riwayat/{pelanggaranSiswa}/batal', [RiwayatPelanggaranController::class, 'cancel'])->name('violations.cancel');
        Route::get('catatan-saya', [CatatanSayaController::class, 'index'])->name('my-notes.index');
        Route::get('catatan-saya/{pelanggaranSiswa}', [CatatanSayaController::class, 'show'])->name('my-notes.show');
        Route::get('siswa', [SiswaController::class, 'index'])->name('students.index');
        Route::get('siswa/{siswa}', [SiswaController::class, 'show'])->name('students.show');
    });
