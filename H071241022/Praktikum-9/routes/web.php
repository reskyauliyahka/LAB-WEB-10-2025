<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

Route::get('/', fn() => redirect()->route('products.index'));

Route::resource('categories', CategoryController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('products', ProductController::class);

Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('stocks/transfer', [StockController::class, 'transferForm'])->name('stocks.transferForm');
Route::post('stocks/transfer', [StockController::class, 'transfer'])->name('stocks.transfer');
