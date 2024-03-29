<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'brand' => Str::random(),
            'description' => Str::random(),
            'price' => fake()->numberBetween(50, 500),
            'count_in_stock' => fake()->numberBetween(1, 10),
            'rating' => fake()->numberBetween(1, 5),
            'num_of_reviews' => fake()->numberBetween(0, 5),
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id
        ];
    }
}
