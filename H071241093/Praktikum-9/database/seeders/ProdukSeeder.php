<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductDetail; // <-- BARIS INI YANG DITAMBAHKAN
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Pastikan ini ada

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Hapus data lama (opsional, tapi bagus untuk testing)
        // Cek driver database yang sedang digunakan
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            // Perintah untuk SQLite
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // Truncate semua tabel
        Category::truncate();
        Product::truncate();
        ProductDetail::truncate(); // <-- Sekarang baris ini aman
        Warehouse::truncate();
        DB::table('products_warehouses')->truncate();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            // Perintah untuk SQLite
            DB::statement('PRAGMA foreign_keys = ON;');
        }


        // 2. Buat Kategori
        $kategoriElektronik = Category::create([
            'name' => 'Elektronik',
            'description' => 'Barang elektronik dengan kualitas baik'
        ]);

        $kategoriRT = Category::create([
            'name' => 'Rumah Tangga',
            'description' => 'Barang rumah tangga dengan kualitas mantap'
        ]);

        // 3. Buat Gudang
        $gudangMakassar = Warehouse::create([
            'name' => 'Gudang Makassar',
            'location' => 'Jl. Perintis Kemerdekaan KM. 10'
        ]);
        
        $gudangGowa = Warehouse::create([
            'name' => 'Gudang Gowa',
            'location' => 'Jl. Poros Malino'
        ]);


        // 4. Buat Produk 1 (TV)
        $productTV = Product::create([
            'name' => 'Smart TV Samsung 24 Inch',
            'price' => 1500000,
            'category_id' => $kategoriElektronik->id
        ]);
        // Buat Detail untuk TV
        $productTV->productDetail()->create([
            'description' => 'Ini adalah deskripsi dummy untuk Smart TV',
            'weight' => 5.50, // Berat dalam KG
            'size' => '24 Inch'
        ]);
        // Masukkan Stok untuk TV
        // 50 di Makassar, 50 di Gowa
        $productTV->warehouses()->attach([
            $gudangMakassar->id => ['quantity' => 50],
            $gudangGowa->id => ['quantity' => 50],
        ]);


        // 5. Buat Produk 2 (Charger)
        $productCharger = Product::create([
            'name' => 'Charger Apple',
            'price' => 2000000,
            'category_id' => $kategoriElektronik->id
        ]);
        // Buat Detail untuk Charger
        $productCharger->productDetail()->create([
            'description' => 'Ini adalah deskripsi dummy untuk Charger Apple',
            'weight' => 0.20,
            'size' => 'Small'
        ]);
        // Masukkan Stok untuk Charger
        // 30 di Makassar, 20 di Gowa
        $productCharger->warehouses()->attach([
            $gudangMakassar->id => ['quantity' => 30],
            $gudangGowa->id => ['quantity' => 20],
        ]);


        // 6. Buat Produk 3 (Sapu)
        $productSapu = Product::create([
            'name' => 'Sapu Ijuk',
            'price' => 50000,
            'category_id' => $kategoriRT->id
        ]);
        // Buat Detail untuk Sapu
        $productSapu->productDetail()->create([
            'description' => 'Ini adalah deskripsi dummy untuk Sapu Ijuk',
            'weight' => 0.80,
            'size' => '120 cm'
        ]);
        // Masukkan Stok untuk Sapu
        // 500 hanya di Gudang Makassar
        $productSapu->warehouses()->attach([
            $gudangMakassar->id => ['quantity' => 500],
        ]);
    }
}