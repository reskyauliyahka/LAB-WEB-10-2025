<?php

use App\Http\Controllers\CategoryController; // Ganti nama
use App\Http\Controllers\ProductController;  // Ganti nama
use App\Http\Controllers\WarehouseController; // Baru
use App\Http\Controllers\StockController; // Baru
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.beranda');
});

Route::get('/about',function(){
    return view('pages.about',[
        'nama'=>'mochammad ardiansyah',
        'umur'=>20,
        'alamat'=>'Malaysia'
    ]);
});

Route::view('/contact','pages.contact');

// Ganti dari Route::resource('kategori', KategoriController::class);
Route::resource('categories', CategoryController::class);

// Ganti dari routing manual /product
Route::resource('products', ProductController::class);

// Rute baru untuk CRUD Gudang
Route::resource('warehouses', WarehouseController::class);

// Rute baru untuk Manajemen Stok
Route::get('stock', [StockController::class, 'index'])->name('stock.index');
Route::get('stock/transfer', [StockController::class, 'showTransferForm'])->name('stock.transfer.form');
Route::post('stock/transfer', [StockController::class, 'transfer'])->name('stock.transfer.submit');