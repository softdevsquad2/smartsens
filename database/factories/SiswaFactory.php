<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_kelas' => \App\Models\Kelas::factory(),
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'nisn' => fake()->unique()->numerify('##########'),
            'card_code' => fake()->unique()->bothify('????-####'),
            'no_hp_ortu' => fake()->phoneNumber(),
        ];
    }
}
