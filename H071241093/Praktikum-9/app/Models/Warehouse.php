<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    // Nama tabel sesuai migration baru
    protected $table = 'warehouses';

    // Kolom yang boleh diisi
    protected $fillable = [
        'name',
        'location',
    ];

    /**
     * Relasi N:M (Gudang dan Produk)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_warehouses', 'warehouse_id', 'product_id')
                    // Ini PENTING: agar kita bisa akses kolom 'quantity' di tabel pivot
                    ->withPivot('quantity');
    }

    
}