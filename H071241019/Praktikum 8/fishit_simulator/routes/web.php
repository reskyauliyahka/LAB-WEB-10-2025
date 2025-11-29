<?php

use App\Http\Controllers\FishController;

Route::get('/', [FishController::class, 'index']);
Route::resource('fishes', FishController::class);
