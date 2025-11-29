<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fish;

class FishSeeder extends Seeder
{
    public function run(): void
    {
        Fish::insert([
            [
                'name' => 'Koi Biasa',
                'rarity' => 'Common',
                'base_weight_min' => 0.5,
                'base_weight_max' => 2,
                'sell_price_per_kg' => 100,
                'catch_probability' => 75,
                'description' => 'Ikan koi yang umum dijumpai.',
            ],
            [
                'name' => 'Naga Laut',
                'rarity' => 'Legendary',
                'base_weight_min' => 12,
                'base_weight_max' => 30,
                'sell_price_per_kg' => 10000,
                'catch_probability' => 1,
                'description' => 'Ikan legendaris yang sangat langka.',
            ],
        ]);
    }
}
