<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment' => Str::random(),
            'rating' => fake()->numberBetween(1, 5),
            'product_id' => Product::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}
