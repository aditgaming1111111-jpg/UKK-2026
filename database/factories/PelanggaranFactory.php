<?php

namespace Database\Factories;

use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pelanggaran>
 */
class PelanggaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => PelanggaranKategori::factory(),
            'kode' => fake()->unique()->bothify('PLG-####'),
            'nama_pelanggaran' => fake()->sentence(3),
            'deskripsi' => fake()->sentence(),
            'poin' => fake()->numberBetween(1, 50),
            'status_aktif' => true,
        ];
    }
}
