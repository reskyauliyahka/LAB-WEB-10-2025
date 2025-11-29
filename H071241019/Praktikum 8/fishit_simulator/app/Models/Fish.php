<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
   protected $fillable = [
    'name',
    'rarity',
    'base_weight_min',
    'base_weight_max',
    'sell_price_per_kg',
    'catch_probability',
    'description',
];

}
