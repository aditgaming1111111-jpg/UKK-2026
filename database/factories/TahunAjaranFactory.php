<?php

namespace Database\Factories;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TahunAjaran>
 */
class TahunAjaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_tahun_ajaran' => now()->year.'/'.(now()->year + 1),
            'tanggal_mulai' => now()->startOfYear()->toDateString(),
            'tanggal_selesai' => now()->endOfYear()->toDateString(),
            'status_aktif' => true,
        ];
    }
}
