<?php

namespace Database\Factories;

use App\Models\StudentTransitionBatch;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentTransitionBatch>
 */
class StudentTransitionBatchFactory extends Factory
{
    /**
     * Membuat snapshot batch transisi untuk kebutuhan pengujian.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batch_id' => fake()->uuid(),
            'tahun_ajaran_asal_id' => TahunAjaran::factory(),
            'tahun_ajaran_tujuan_id' => TahunAjaran::factory(),
            'jenis_proses' => 'naik',
            'status' => StudentTransitionBatch::STATUS_COMPLETED,
            'jumlah_diproses' => 1,
            'jumlah_dikecualikan' => 0,
            'user_id' => User::factory()->state(['role' => 'admin']),
            'data_snapshot' => [],
            'dibatalkan_oleh_user_id' => null,
            'dibatalkan_pada' => null,
        ];
    }
}
