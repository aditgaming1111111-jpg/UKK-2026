<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KelasSiswa>
 */
class KelasSiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'siswa_id' => Siswa::factory(),
            'kelas_id' => Kelas::factory(),
            'tahun_ajaran_id' => TahunAjaran::factory(),
            'tanggal_mulai' => now()->startOfYear()->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ];
    }
}
