<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\PelanggaranController;
use App\Http\Controllers\Admin\PelanggaranKategoriController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\StudentTransitionController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\WaliKelasController;
use App\Http\Controllers\RiwayatPelanggaranController;
use Illuminate\Support\Facades\Route;

// Seluruh rute administrasi dibatasi untuk pengguna dengan peran admin.
Route::prefix('admin')
    ->name('admin.')
    ->middleware('role:admin')
    ->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        // Laporan menyediakan tampilan ringkasan serta keluaran cetak dan PDF.
        Route::get('laporan', [LaporanController::class, 'index'])->name('reports.index');
        Route::get('laporan/cetak', [LaporanController::class, 'print'])->name('reports.print');
        Route::get('laporan/pdf', [LaporanController::class, 'pdf'])->name('reports.pdf');
        Route::get('data-pelanggaran/{pelanggaranSiswa}/surat', [RiwayatPelanggaranController::class, 'notice'])->name('student-violations.notice');
        Route::resource('data-pelanggaran', RiwayatPelanggaranController::class)
            ->parameters(['data-pelanggaran' => 'pelanggaranSiswa'])
            ->except(['create', 'store', 'destroy'])
            ->names('student-violations');
        Route::patch('data-pelanggaran/{pelanggaranSiswa}/batal', [RiwayatPelanggaranController::class, 'cancel'])
            ->name('student-violations.cancel');
        Route::get('log-pelanggaran', [LogAktivitasController::class, 'index'])->name('audit-logs.index');

        // Pengelolaan siswa mencakup status dan penempatan ke kelas.
        Route::get('siswa-arsip', [SiswaController::class, 'archivedIndex'])->name('students.archived.index');
        Route::get('siswa-arsip/{studentId}', [SiswaController::class, 'showArchived'])->whereNumber('studentId')->name('students.archived.show');
        Route::patch('siswa-arsip/{studentId}/pulihkan', [SiswaController::class, 'restore'])->whereNumber('studentId')->name('students.archived.restore');
        Route::resource('siswa', SiswaController::class)->parameters(['siswa' => 'siswa'])->names('students');
        Route::patch('siswa/{siswa}/status', [SiswaController::class, 'updateStatus'])->name('students.status');
        Route::patch('siswa/{siswa}/arsipkan', [SiswaController::class, 'archive'])->name('students.archive');
        Route::post('siswa/{siswa}/penempatan', [SiswaController::class, 'storePlacement'])->name('students.placements.store');
        Route::post('siswa/{siswa}/pindah-kelas', [SiswaController::class, 'moveClass'])->name('students.move-class');
        Route::patch('siswa/{siswa}/penempatan/{placementId}', [SiswaController::class, 'updatePlacement'])->name('students.placements.update');
        Route::delete('siswa/{siswa}/penempatan/{placementId}', [SiswaController::class, 'destroyPlacement'])->name('students.placements.destroy');

        // Pengelolaan guru dan akun terkaitnya.
        Route::resource('guru', GuruController::class)->parameters(['guru' => 'guru'])->except(['destroy'])->names('teachers');
        Route::delete('guru/{guru}', [GuruController::class, 'destroy'])->name('teachers.destroy');
        Route::patch('guru/{guru}/nonaktifkan', [GuruController::class, 'deactivate'])->name('teachers.deactivate');
        Route::put('guru/{guru}/akun', [GuruController::class, 'updateAccount'])->name('teachers.account.update');
        Route::patch('guru/{guru}/aktifkan', [GuruController::class, 'activate'])->name('teachers.activate');

        // Kelas, tahun ajaran, dan proses perpindahan siswa antar tahun.
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->names('classes');
        Route::resource('tahun-ajaran', TahunAjaranController::class)
            ->parameters(['tahun-ajaran' => 'tahunAjaran'])
            ->names('academic-years');
        Route::get('tahun-ajaran/{tahunAjaran}/proses-siswa', [StudentTransitionController::class, 'show'])
            ->name('academic-years.student-transitions');
        Route::post('tahun-ajaran/{tahunAjaran}/proses-siswa', [StudentTransitionController::class, 'store'])
            ->name('academic-years.student-transitions.store');
        Route::patch('tahun-ajaran/{tahunAjaran}/proses-siswa/{batchId}/batalkan', [StudentTransitionController::class, 'undo'])
            ->whereUuid('batchId')
            ->name('academic-years.student-transitions.undo');

        // Kategori dan jenis pelanggaran dapat dikelola serta diaktifkan ulang.
        Route::resource('kategori-pelanggaran', PelanggaranKategoriController::class)
            ->parameters(['kategori-pelanggaran' => 'pelanggaranKategori'])
            ->names('violation-categories');
        Route::patch('kategori-pelanggaran/{pelanggaranKategori}/status', [PelanggaranKategoriController::class, 'toggleStatus'])
            ->name('violation-categories.status');
        Route::resource('jenis-pelanggaran', PelanggaranController::class)
            ->parameters(['jenis-pelanggaran' => 'pelanggaran'])
            ->names('violations');
        Route::patch('jenis-pelanggaran/{pelanggaran}/status', [PelanggaranController::class, 'toggleStatus'])
            ->name('violations.status');

        // Riwayat penugasan wali kelas per kelas dan tahun ajaran.
        Route::get('wali-kelas', [WaliKelasController::class, 'index'])->name('homeroom-teachers.index');
        Route::get('wali-kelas/create', [WaliKelasController::class, 'create'])->name('homeroom-teachers.create');
        Route::post('wali-kelas', [WaliKelasController::class, 'store'])->name('homeroom-teachers.store');
        Route::get('wali-kelas/{waliKelas}', [WaliKelasController::class, 'show'])->name('homeroom-teachers.show');
        Route::patch('wali-kelas/{waliKelas}', [WaliKelasController::class, 'updateDates'])->name('homeroom-teachers.update');
        Route::patch('wali-kelas/{waliKelas}/akhiri', [WaliKelasController::class, 'close'])->name('homeroom-teachers.close');
    });
