<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location']; // 

    // Relasi: Warehouse memiliki banyak Product
    public function products()
    {
        // belongsToMany digunakan untuk relasi N:M
        // 'product_warehouse' adalah nama tabel pivotnya
        // withPivot('quantity') agar kita bisa ambil data stok nanti
        return $this->belongsToMany(Product::class, 'product_warehouse')
                    ->withPivot('quantity');
    }
}