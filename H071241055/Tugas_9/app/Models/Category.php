<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['name', 'description']; // 

    // Relasi: Satu Category memiliki banyak Product
    public function products()
    {
        return $this->hasMany(Product::class); 
    }
}