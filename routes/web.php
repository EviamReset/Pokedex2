<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return view('index');
});


Route::get('/table', [PokemonController::class, 'pokemonsWithTypes']);
Route::post('/store', [PokemonController::class, 'store'])->name('pokemon.store');
Route::delete('/pokemon/{id}', [PokemonController::class, 'destroy'])->name('pokemon.destroy');
Route::put('/pokemon/{id}', [PokemonController::class, 'update'])->name('pokemon.update');