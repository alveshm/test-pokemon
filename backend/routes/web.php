<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

Route::controller(PokemonController::class)->group(function () {
    Route::get('/detail/{id}', 'detail');
    Route::get('/list', 'list');
    Route::get('/populate', 'populate');
});