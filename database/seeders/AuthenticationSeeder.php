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
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            throw new \RuntimeException('AuthenticationSeeder hanya boleh dijalankan pada APP_ENV=local.');
        }

        $adminPassword = config('app.seed_admin_password');
        $guruPassword = config('app.seed_guru_password');

        if (! is_string($adminPassword) || mb_strlen($adminPassword) < 8) {
            throw new \RuntimeException('SEED_ADMIN_PASSWORD wajib diisi minimal 8 karakter di .env lokal.');
        }

        if (! is_string($guruPassword) || mb_strlen($guruPassword) < 8) {
            throw new \RuntimeException('SEED_GURU_PASSWORD wajib diisi minimal 8 karakter di .env lokal.');
        }

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
                'nip' => '1980010120260001',
                'nama' => $guru->nama,
                'email' => $guru->email,
                'status_aktif' => true,
            ],
        );
    }
}
