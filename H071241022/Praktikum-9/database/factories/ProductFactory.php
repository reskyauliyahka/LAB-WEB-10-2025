<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'price' => $this->faker->randomFloat(2, 100, 20000),
            'category_id' => Category::factory(), // creates a related category
        ];
    }
}
