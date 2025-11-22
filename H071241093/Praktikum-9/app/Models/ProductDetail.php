<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    // Nama tabel sesuai migration baru
    protected $table = 'product_details';

    // Kolom yang boleh diisi
    protected $fillable = [
        'product_id',
        'description',
        'weight',
        'size',
    ];

    /**
     * Relasi 1:1 (Satu Detail milik satu Produk)
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}