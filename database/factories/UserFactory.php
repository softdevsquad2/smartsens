<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->username(),
            'password' => Hash::make('password'),
            'role' => fake()->randomElement(['admin', 'guru', 'operator', 'siswa', 'ketua', 'uks', 'piket']),
            'card_code' => fake()->unique()->numberBetween(100000000, 999999999),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a wali kelas.
     */
    public function waliKelas(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'id_wali_kelas' => \App\Models\WaliKelas::factory(),
                'role' => 'wali_kelas',
            ];
        });
    }

    /**
     * Indicate that the user is a siswa.
     */
    public function siswa(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'id_siswa' => \App\Models\Siswa::factory(),
                'role' => 'siswa',
            ];
        });
    }
}
