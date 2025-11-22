<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Nama tabel sesuai migration baru
    protected $table = 'categories';

    // Kolom yang boleh diisi
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi 1:N (Satu Kategori punya banyak Produk)
     */
    public function products()
    {
        // karena kita pakai onDelete('set null'),
        // relasi ini akan otomatis di-handle saat kategori dihapus
        return $this->hasMany(Product::class, 'category_id');
    }
}