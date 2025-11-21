<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WaliKelas>
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
            'id_kelas' => \App\Models\Kelas::factory(),
            'nama' => fake()->name(),
            'nip' => fake()->unique()->numberBetween(100000000000, 999999999999),
        ];
    }
}
