<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     * Hash kata sandi disimpan bersama agar pemanggilan factory berikutnya konsisten.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     * Membuat akun contoh berperan guru dengan alamat email unik.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'guru',
        ];
    }
}
