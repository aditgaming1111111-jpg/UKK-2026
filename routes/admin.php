<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PelanggaranController;
use App\Http\Controllers\Admin\PelanggaranKategoriController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\WaliKelasController;
use App\Http\Controllers\LogPelanggaranController;
use App\Http\Controllers\RiwayatPelanggaranController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware('role:admin')
    ->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('laporan', [LaporanController::class, 'index'])->name('reports.index');
        Route::get('laporan/cetak', [LaporanController::class, 'print'])->name('reports.print');
        Route::get('laporan/pdf', [LaporanController::class, 'pdf'])->name('reports.pdf');
        Route::resource('data-pelanggaran', RiwayatPelanggaranController::class)
            ->parameters(['data-pelanggaran' => 'pelanggaranSiswa'])
            ->except(['create', 'store', 'destroy'])
            ->names('student-violations');
        Route::patch('data-pelanggaran/{pelanggaranSiswa}/batal', [RiwayatPelanggaranController::class, 'cancel'])
            ->name('student-violations.cancel');
        Route::get('log-pelanggaran', [LogPelanggaranController::class, 'index'])->name('audit-logs.index');
        Route::resource('siswa', SiswaController::class)->parameters(['siswa' => 'siswa'])->names('students');
        Route::patch('siswa/{siswa}/status', [SiswaController::class, 'updateStatus'])->name('students.status');
        Route::post('siswa/{siswa}/penempatan', [SiswaController::class, 'storePlacement'])->name('students.placements.store');
        Route::resource('guru', GuruController::class)->parameters(['guru' => 'guru'])->except(['destroy'])->names('teachers');
        Route::delete('guru/{guru}', [GuruController::class, 'destroy'])->name('teachers.destroy');
        Route::put('guru/{guru}/akun', [GuruController::class, 'updateAccount'])->name('teachers.account.update');
        Route::patch('guru/{guru}/aktifkan', [GuruController::class, 'activate'])->name('teachers.activate');
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->names('classes');
        Route::resource('tahun-ajaran', TahunAjaranController::class)
            ->parameters(['tahun-ajaran' => 'tahunAjaran'])
            ->except(['destroy'])
            ->names('academic-years');
        Route::resource('kategori-pelanggaran', PelanggaranKategoriController::class)
            ->parameters(['kategori-pelanggaran' => 'pelanggaranKategori'])
            ->except(['destroy'])
            ->names('violation-categories');
        Route::patch('kategori-pelanggaran/{pelanggaranKategori}/status', [PelanggaranKategoriController::class, 'toggleStatus'])
            ->name('violation-categories.status');
        Route::resource('jenis-pelanggaran', PelanggaranController::class)
            ->parameters(['jenis-pelanggaran' => 'pelanggaran'])
            ->except(['destroy'])
            ->names('violations');
        Route::patch('jenis-pelanggaran/{pelanggaran}/status', [PelanggaranController::class, 'toggleStatus'])
            ->name('violations.status');
        Route::get('wali-kelas', [WaliKelasController::class, 'index'])->name('homeroom-teachers.index');
        Route::get('wali-kelas/create', [WaliKelasController::class, 'create'])->name('homeroom-teachers.create');
        Route::post('wali-kelas', [WaliKelasController::class, 'store'])->name('homeroom-teachers.store');
        Route::get('wali-kelas/{waliKelas}', [WaliKelasController::class, 'show'])->name('homeroom-teachers.show');
        Route::patch('wali-kelas/{waliKelas}/akhiri', [WaliKelasController::class, 'close'])->name('homeroom-teachers.close');
    });
