<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{
    protected $fillable = ['name', 'location'];

    public function products()
{
    return $this->belongsToMany(Product::class, 'products_warehouses')
                ->withPivot('quantity')
                ->withTimestamps();
}
}