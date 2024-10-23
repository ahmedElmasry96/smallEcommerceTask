<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'total_price' => $this->faker->randomFloat(2, 50, 500),
            'tax_value' => $this->faker->randomFloat(2, 1, 20),
            'discount_value' => $this->faker->randomFloat(2, 0, 30),
            'payment_status' => 2,
            'payment_method' => 1,
        ];
    }
}
