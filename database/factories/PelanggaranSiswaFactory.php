<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\KelasSiswa;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PelanggaranSiswa>
 */
class PelanggaranSiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kelas_siswa_id' => KelasSiswa::factory(),
            'pelanggaran_id' => Pelanggaran::factory(),
            'guru_id' => Guru::factory(),
            'tanggal_pelanggaran' => today()->toDateString(),
            'tanggal_dicatat' => now(),
            'poin_saat_dicatat' => fake()->numberBetween(1, 50),
            'keterangan' => fake()->sentence(),
            'tindakan' => null,
            'status' => 'aktif',
        ];
    }
}
