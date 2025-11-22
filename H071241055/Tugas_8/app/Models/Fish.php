<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
    use HasFactory;
    protected $table = 'fishes';

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     * Ini adalah semua field yang akan kita isi dari form Create/Edit [cite: 19-26].
     * Mirip dengan properti $fillable di modul[cite: 216, 246].
     */
    protected $fillable = [
        'name',
        'rarity',
        'base_weight_min',
        'base_weight_max',
        'sell_price_per_kg',
        'catch_probability',
        'description',
    ];

    /**
     * Konversi tipe data otomatis (casting).
     * Ini membantu agar angka desimal dan integer
     * diperlakukan dengan benar.
     * Mirip dengan properti $casts di modul[cite: 221, 254].
     */
    protected $casts = [
        'base_weight_min' => 'decimal:2',
        'base_weight_max' => 'decimal:2',
        'catch_probability' => 'decimal:2',
        'sell_price_per_kg' => 'integer',
    ];

    // --- FITUR BONUS (NILAI PLUS) ---

    /**
     * [NILAI PLUS]
     * Scope untuk filter berdasarkan Rarity.
     * Mirip dengan scopeActive() di modul[cite: 228, 262].
     */
    public function scopeFilterByRarity($query, $rarity)
    {
        // Jika $rarity ada isinya (tidak string kosong atau null)
        if ($rarity) {
            // Terapkan filter where
            return $query->where('rarity', $rarity);
        }
        // Jika tidak, kembalikan query aslinya
        return $query;
    }

    /**
     * [NILAI PLUS]
     * Accessor untuk format harga jual (sell_price_per_kg).
     * Mirip getFormattedPriceAttribute() di modul [cite: 270-272].
     */
    public function getFormattedPriceAttribute(): string
    {
        // Format angka menjadi "1.000 Coins"
        return number_format($this->sell_price_per_kg, 0, ',', '.') . ' Coins';
    }

    /**
     * [NILAI PLUS]
     * Accessor untuk format berat (base_weight_min dan base_weight_max).
     */
    public function getFormattedWeightRangeAttribute(): string
    {
        // Format angka menjadi "1.50kg - 10.00kg"
        return number_format($this->base_weight_min, 2) . 'kg - ' . number_format($this->base_weight_max, 2) . 'kg';
    }
}