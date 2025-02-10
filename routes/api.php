<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

Route::apiResource('pokemon', PokemonController::class);
Route::get('pokemonAll', [PokemonController::class, 'pokemonsWithTypes']);
