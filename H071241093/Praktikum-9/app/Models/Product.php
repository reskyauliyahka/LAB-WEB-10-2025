<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Nama tabel sesuai migration baru
    protected $table = 'products';

    // Kolom yang boleh diisi
    protected $fillable = [
        'name',
        'price',
        'category_id',
    ];

    /**
     * Relasi N:1 (Satu Produk milik satu Kategori)
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relasi 1:1 (Satu Produk punya satu Detail)
     */
    public function productDetail()
    {
        // 'product_id' adalah foreign key di tabel product_details
        return $this->hasOne(ProductDetail::class, 'product_id');
    }

    /**
     * Relasi N:M (Produk dan Gudang)
     */
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'products_warehouses', 'product_id', 'warehouse_id')
                    // Ini PENTING: agar kita bisa akses kolom 'quantity' di tabel pivot
                    ->withPivot('quantity');
    }
}