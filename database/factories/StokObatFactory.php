<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StokObat>
 */
class StokObatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_obat' => \App\Models\Obat::factory(),
            'jumlah' => fake()->numberBetween(1, 100),
            'tanggal_masuk' => fake()->dateTime(),
            'expired_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
        ];
    }
}
