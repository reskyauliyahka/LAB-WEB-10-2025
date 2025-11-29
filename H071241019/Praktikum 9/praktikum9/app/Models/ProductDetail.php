<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    // PERBAIKAN: Tentukan nama tabel yang benar
    protected $table = 'productdetails';

    protected $fillable = [
        'product_id',
        'description', 
        'weight',
        'size',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}