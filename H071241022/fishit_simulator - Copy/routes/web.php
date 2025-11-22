<?php

use App\Http\Controllers\FishController;

Route::get('/', function () {
    return redirect()->route('fishes.index');
});

Route::resource('fishes', FishController::class);
