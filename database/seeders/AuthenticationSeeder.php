<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthenticationSeeder extends Seeder
{
    /**
     * Local development credentials:
     * admin@sekolahku.test / AdminSekolahKu#2026
     * guru@sekolahku.test / GuruSekolahKu#2026
     */
    private const ADMIN_PASSWORD = 'AdminSekolahKu#2026';

    private const GURU_PASSWORD = 'GuruSekolahKu#2026';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            throw new \RuntimeException('AuthenticationSeeder hanya boleh dijalankan pada APP_ENV=local.');
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@sekolahku.test'],
            [
                'nama' => 'Administrator SekolahKu',
                'password' => Hash::make(self::ADMIN_PASSWORD),
                'role' => 'admin',
            ],
        );

        $guru = User::updateOrCreate(
            ['email' => 'guru@sekolahku.test'],
            [
                'nama' => 'Guru SekolahKu',
                'password' => Hash::make(self::GURU_PASSWORD),
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
