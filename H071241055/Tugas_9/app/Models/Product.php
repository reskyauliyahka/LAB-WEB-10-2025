<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'category_id']; // 

    // 1. Relasi ke Category (Inverse One-to-Many)
    public function category()
    {
        return $this->belongsTo(Category::class); // [cite: 328]
    }

    // 2. Relasi ke ProductDetail (One-to-One)
    public function detail()
    {
        return $this->hasOne(ProductDetail::class); // [cite: 324]
    }

    // 3. Relasi ke Warehouse (Many-to-Many)
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                    ->withPivot('quantity'); // Penting untuk fitur stok nanti [cite: 4]
    }
}