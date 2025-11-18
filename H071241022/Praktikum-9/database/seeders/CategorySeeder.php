<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Gadgets, computers, and more'],
            ['name' => 'Furniture', 'description' => 'Chairs, tables, beds, etc.'],
            ['name' => 'Clothing', 'description' => 'Men and women fashion'],
            ['name' => 'Sports', 'description' => 'Outdoor and indoor sport equipment'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
