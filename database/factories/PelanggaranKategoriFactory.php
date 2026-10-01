<?php

namespace Database\Factories;

use App\Models\PelanggaranKategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PelanggaranKategori>
 */
class PelanggaranKategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kategori' => fake()->unique()->words(2, true),
            'deskripsi' => fake()->sentence(),
            'status_aktif' => true,
        ];
    }
}
