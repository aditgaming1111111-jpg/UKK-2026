<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Menginisialisasi pengaturan dan pendaftaran layanan yang dibutuhkan aplikasi.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan layanan aplikasi ke dalam container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Menjalankan pengaturan layanan saat aplikasi mulai.
     */
    public function boot(): void
    {
        //
    }
}
