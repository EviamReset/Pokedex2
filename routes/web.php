<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return view('index');
});


Route::get('/table', [PokemonController::class, 'pokemonsWithTypes']);