<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaliKelas>
 */
class WaliKelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guru_id' => Guru::factory(),
            'kelas_id' => Kelas::factory(),
            'tahun_ajaran_id' => TahunAjaran::factory(),
            'tanggal_mulai' => now()->startOfYear()->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ];
    }
}
