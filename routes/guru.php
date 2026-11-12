<?php

use App\Http\Controllers\Guru\CatatanSayaController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\PelanggaranSiswaController;
use App\Http\Controllers\Guru\RekapKelasController;
use App\Http\Controllers\Guru\RekapPoinController;
use App\Http\Controllers\Guru\SiswaController;
use App\Http\Controllers\RiwayatPelanggaranController;
use Illuminate\Support\Facades\Route;

// Rute guru hanya dapat diakses oleh pengguna dengan peran guru.
Route::prefix('guru')
    ->name('guru.')
    ->middleware('role:guru')
    ->group(function (): void {
        Route::get('/', GuruDashboardController::class)->name('dashboard');

        // Rekap poin dan kelas menyediakan ekspor serta rincian siswa.
        Route::get('rekap-poin', [RekapPoinController::class, 'index'])->name('point-recap.index');
        Route::get('rekap-kelas', [RekapKelasController::class, 'index'])->name('class-recap.index');
        Route::get('rekap-kelas/{tahunAjaran}/{kelas}/pdf', [RekapKelasController::class, 'pdf'])->whereNumber('tahunAjaran')->whereNumber('kelas')->name('class-recap.pdf');
        Route::get('rekap-kelas/{tahunAjaran}/{kelas}/excel', [RekapKelasController::class, 'excel'])->whereNumber('tahunAjaran')->whereNumber('kelas')->name('class-recap.excel');
        Route::get('rekap-kelas/{tahunAjaran}/{kelas}/{siswa}', [RekapKelasController::class, 'show'])->whereNumber('tahunAjaran')->whereNumber('kelas')->whereNumber('siswa')->name('class-recap.show');

        // Guru dapat mencatat pelanggaran dan meninjau riwayatnya.
        Route::get('pelanggaran/create', [PelanggaranSiswaController::class, 'create'])->name('violations.create');
        Route::get('pelanggaran/cari-siswa', [PelanggaranSiswaController::class, 'searchStudents'])->name('violations.student-search');
        Route::post('pelanggaran', [PelanggaranSiswaController::class, 'store'])->name('violations.store');
        Route::get('pelanggaran/riwayat', [RiwayatPelanggaranController::class, 'index'])->name('violations.index');
        Route::get('pelanggaran/riwayat/{pelanggaranSiswa}', [RiwayatPelanggaranController::class, 'show'])->name('violations.show');

        // Catatan pelanggaran yang dibuat guru dapat ditinjau dan diperbarui.
        Route::get('catatan-saya', [CatatanSayaController::class, 'index'])->name('my-notes.index');
        Route::get('catatan-saya/{pelanggaranSiswa}', [CatatanSayaController::class, 'show'])->name('my-notes.show');
        Route::get('catatan-saya/{pelanggaranSiswa}/edit', [RiwayatPelanggaranController::class, 'edit'])->name('my-notes.edit');
        Route::put('catatan-saya/{pelanggaranSiswa}', [RiwayatPelanggaranController::class, 'update'])->name('my-notes.update');
        Route::patch('catatan-saya/{pelanggaranSiswa}/batal', [RiwayatPelanggaranController::class, 'cancel'])->name('my-notes.cancel');

        // Rincian siswa mengikuti kelas dan tahun ajaran yang dipilih.
        Route::get('siswa/{siswa}', [SiswaController::class, 'show'])->name('students.show');
    });
