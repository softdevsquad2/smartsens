<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
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
            'id_jurusan' => \App\Models\Jurusan::factory(),
            'nama_kelas' => fake()->randomElement(['X', 'XI', 'XII']) . ' ' . fake()->randomElement(['RPL', 'TKJ', 'APKJ']),
        ];
    }
}
