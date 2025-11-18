<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;

class ProductWarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = Warehouse::all();
        $products = Product::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                ProductWarehouse::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => rand(5, 50),
                ]);
            }
        }
    }
}
