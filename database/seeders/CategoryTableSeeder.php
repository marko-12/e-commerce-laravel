<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Category::BASIC_CATEGORIES as $id => $name)
        {
            Category::factory()->create([
                'id' => $id,
                'name' => $name
            ]);
        }
    }
}
