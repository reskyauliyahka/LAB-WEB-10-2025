<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Route Resource (Otomatis buat index, create, store, edit, update, destroy) [cite: 556]
Route::resource('categories', CategoryController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('products', ProductController::class);

// Route Khusus Manajemen Stok [cite: 22]
Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
// Menampilkan form transfer stok
Route::get('stocks/transfer', [StockController::class, 'transferForm'])->name('stocks.transfer');
// Memproses transfer stok (+ atau -)
Route::post('stocks/transfer', [StockController::class, 'processTransfer'])->name('stocks.processTransfer');