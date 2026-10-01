<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kelas' => fake()->randomElement(['VII A', 'VIII B', 'IX C', 'X IPA 1']),
            'tingkat' => fake()->randomElement(['VII', 'VIII', 'IX', 'X']),
            'jurusan' => null,
        ];
    }
}
