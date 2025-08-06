<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * 対応するモデル
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'product_name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(100, 10000),
            'shipping_fee' => $this->faker->numberBetween(0, 1000),
            'quantity' => $this->faker->numberBetween(1, 5),
            'user_id' => User::factory(),
            'total_price' => $this->faker->numberBetween(1000, 50000),
            'statusItem' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'delivered']),
            'productType' => $this->faker->randomElement(['single', 'set']),
            'selected_product_sets' => [],
        ];
    }
}