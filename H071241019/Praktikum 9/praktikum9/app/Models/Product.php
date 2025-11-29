<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function detail(): HasOne
    {
        // PERBAIKAN: Specify the correct table/model
        return $this->hasOne(ProductDetail::class, 'product_id');
    }

    public function warehouses()
{
    return $this->belongsToMany(Warehouse::class, 'products_warehouses')
                ->withPivot('quantity')
                ->withTimestamps();
}

    // total stok semua gudang
    public function totalStock(): int
    {
        return $this->warehouses()->sum('products_warehouses.quantity');
    }
}