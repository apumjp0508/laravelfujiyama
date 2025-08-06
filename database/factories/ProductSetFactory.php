<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSet>
 */
class ProductSetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'img' => $this->faker->imageUrl(640, 480, 'products', true),
            'stock' => $this->faker->numberBetween(0, 100),
            'widthSize' => $this->faker->numberBetween(50, 200),
            'heightSize' => $this->faker->numberBetween(50, 200),
        ];
    }
}
