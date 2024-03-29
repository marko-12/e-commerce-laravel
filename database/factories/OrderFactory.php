<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country' => fake()->country(),
            'city' => fake()->city(),
            'address' => fake()->address(),
            'postal_code' => fake()->numberBetween(11000, 21000),
            'delivered' => false,
            'delivered_at' => null,
            'paid' => false,
            'paid_at' => null,
            'pay_before_shipping' => false,
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}
