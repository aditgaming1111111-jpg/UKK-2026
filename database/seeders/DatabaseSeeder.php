<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Menjalankan seeder akun autentikasi untuk data awal aplikasi.
     */
    public function run(): void
    {
        $this->call(AuthenticationSeeder::class);
    }
}
