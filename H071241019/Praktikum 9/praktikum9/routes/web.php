<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

// Redirect root to products page
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Categories Management
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Warehouses Management
Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');

// Products Management
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Stock Management
Route::get('/stock', [StockController::class, 'index'])->name('stocks.index');
Route::get('/stock/adjustment', [StockController::class, 'create'])->name('stocks.create');
Route::post('/stock/adjustment', [StockController::class, 'store'])->name('stocks.store');
Route::get('/stock/{productId}/warehouse/{warehouseId}', [StockController::class, 'getStock'])->name('stocks.getStock');