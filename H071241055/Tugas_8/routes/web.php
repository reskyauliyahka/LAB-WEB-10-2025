<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FishController; // <-- 1. Import controller Anda

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 2. Redirect halaman utama (/) ke halaman index ikan
// Mirip dengan [cite: 375-377]
Route::get('/', function () {
    return redirect()->route('fishes.index');
});

// 3. Daftarkan semua route CRUD untuk 'fishes'
// Perintah ini secara otomatis membuat route untuk:
// - fishes.index (GET)
// - fishes.create (GET)
// - fishes.store (POST)
// - fishes.show (GET)
// - fishes.edit (GET)
// - fishes.update (PUT/PATCH)
// - fishes.destroy (DELETE)
Route::resource('fishes', FishController::class);