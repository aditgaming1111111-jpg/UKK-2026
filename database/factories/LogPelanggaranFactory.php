<?php

namespace Database\Factories;

use App\Models\LogPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LogPelanggaran>
 */
class LogPelanggaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pelanggaran_siswa_id' => PelanggaranSiswa::factory(),
            'user_id' => User::factory(),
            'aksi' => 'tambah',
            'data_sebelum' => null,
            'data_sesudah' => fake()->sentence(),
            'alasan' => null,
        ];
    }
}
