<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail' => $this->faker->imageUrl('640', '480', 'people', true),
            'name' => 'Desa ' . $this->faker->words(2, true),
            'about' => $this->faker->paragraph(),
            'headman' => $this->faker->name(),
            'people' => $this->faker->numberBetween(1000, 10000),
            'agricultural_area' => $this->faker->numberBetween(10000, 100000),
            'total_area' => $this->faker->numberBetween(10000, 100000),
        ];
    }
}
