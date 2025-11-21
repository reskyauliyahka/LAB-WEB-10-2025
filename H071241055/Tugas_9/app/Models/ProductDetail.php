<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'description', 'weight', 'size']; // [cite: 4]

    // Relasi: Detail ini milik satu Product
    public function product()
    {
        return $this->belongsTo(Product::class); // [cite: 325]
    }
}