<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $furniture = Category::where('name', 'Furniture')->first();

        $products = [
            [
                'name' => 'Laptop ASUS ROG',
                'price' => 18500000,
                'category_id' => $electronics->id ?? null,
                'detail' => [
                    'description' => 'High-performance gaming laptop',
                    'weight' => 2.3,
                    'size' => '15 inch',
                ]
            ],
            [
                'name' => 'Office Chair',
                'price' => 950000,
                'category_id' => $furniture->id ?? null,
                'detail' => [
                    'description' => 'Ergonomic office chair with lumbar support',
                    'weight' => 7.5,
                    'size' => 'Standard',
                ]
            ],
            [
                'name' => 'Smartphone Samsung A55',
                'price' => 5500000,
                'category_id' => $electronics->id ?? null,
                'detail' => [
                    'description' => 'Mid-range smartphone with AMOLED display',
                    'weight' => 0.35,
                    'size' => '6.4 inch',
                ]
            ],
        ];

        foreach ($products as $p) {
            $product = Product::create([
                'name' => $p['name'],
                'price' => $p['price'],
                'category_id' => $p['category_id'],
            ]);

            ProductDetail::create([
                'product_id' => $product->id,
                'description' => $p['detail']['description'],
                'weight' => $p['detail']['weight'],
                'size' => $p['detail']['size'],
            ]);
        }
    }
}
