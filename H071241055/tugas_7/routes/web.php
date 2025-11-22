<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Ini adalah file untuk mendefinisikan 5 rute utama sesuai ketentuan.
|
*/

// Rute Halaman Home (URL: /) [cite: 29]
Route::get('/', function () {
    return view('home');
})->name('home');

// Rute Halaman Destinasi (URL: /destinasi) [cite: 30]
Route::get('/destinasi', function () {
    return view('destinasi');
})->name('destinasi');

// Rute Halaman Kuliner (URL: /kuliner) [cite: 31]
Route::get('/kuliner', function () {
    return view('kuliner');
})->name('kuliner');

// Rute Halaman Galeri (URL: /galeri) [cite: 32]
Route::get('/galeri', function () {
    return view('galeri');
})->name('galeri');

// Rute Halaman Kontak (URL: /kontak) [cite: 32]
Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');