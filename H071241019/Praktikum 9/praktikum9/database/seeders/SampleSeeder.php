<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Warehouse;

class SampleSeeder extends Seeder
{
    public function run()
    {
        $cat = Category::create(['name'=>'Elektronik','description'=>'Perangkat elektronik']);
        $w1 = Warehouse::create(['name'=>'Gudang Makassar','location'=>'Makassar']);
        $w2 = Warehouse::create(['name'=>'Gudang Gowa','location'=>'Gowa']);

        $p = Product::create(['name'=>'Laptop ASUS','price'=>7500000,'category_id'=>$cat->id]);
        ProductDetail::create(['product_id'=>$p->id,'description'=>'Laptop untuk tugas','weight'=>1.50,'size'=>'15 inch']);

        // attach stock
        $p->warehouses()->attach($w1->id, ['quantity'=>10]);
        $p->warehouses()->attach($w2->id, ['quantity'=>5]);
    }
}
