<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            ['name' => 'Gudang Makassar', 'location' => 'Makassar, Sulawesi Selatan'],
            ['name' => 'Gudang Gowa', 'location' => 'Gowa, Sulawesi Selatan'],
            ['name' => 'Gudang Jakarta', 'location' => 'Jakarta Pusat'],
        ];

        foreach ($warehouses as $w) {
            Warehouse::create($w);
        }
    }
}
