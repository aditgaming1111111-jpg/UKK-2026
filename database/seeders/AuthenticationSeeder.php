<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthenticationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat akun admin dan guru contoh khusus untuk lingkungan lokal.
     */
    public function run(): void
    {
        // Batasi kredensial bawaan seeder agar tidak digunakan di lingkungan lain.
        if (! app()->environment('local')) {
            throw new \RuntimeException('AuthenticationSeeder hanya boleh dijalankan pada APP_ENV=local.');
        }

        // Ambil kata sandi dari konfigurasi lokal dan pastikan nilainya memadai.
        $adminPassword = config('app.seed_admin_password');
        $guruPassword = config('app.seed_guru_password');

        if (! is_string($adminPassword) || mb_strlen($adminPassword) < 8) {
            throw new \RuntimeException('SEED_ADMIN_PASSWORD wajib diisi minimal 8 karakter di .env lokal.');
        }

        if (! is_string($guruPassword) || mb_strlen($guruPassword) < 8) {
            throw new \RuntimeException('SEED_GURU_PASSWORD wajib diisi minimal 8 karakter di .env lokal.');
        }

        // Perbarui atau buat akun agar seeder aman dijalankan berulang kali.
        $admin = User::updateOrCreate(
            ['email' => 'admin@sekolahku.com'],
            [
                'nama' => 'Administrator SekolahKu',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ],
        );

        $guru = User::updateOrCreate(
            ['email' => 'anton@sekolahku.com'],
            [
                'nama' => 'Anton',
                'password' => Hash::make($guruPassword),
                'role' => 'guru',
            ],
        );

        Guru::updateOrCreate(
            ['user_id' => $guru->id],
            [
                'nip' => '198001012026000001',
                'status_kepegawaian' => 'PNS',
                'nama' => $guru->nama,
                'email' => $guru->email,
                'status_aktif' => true,
            ],
        );
    }
}
