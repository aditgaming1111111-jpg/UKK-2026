<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guru>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->unique()->numerify('##########'),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status_aktif' => true,
            'user_id' => User::factory()->state(['role' => 'guru']),
        ];
    }
}
