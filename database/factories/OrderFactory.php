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
            'delivered' => false,
            'delivered_at' => null,
            'paid' => false,
            'paid_at' => null,
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}
