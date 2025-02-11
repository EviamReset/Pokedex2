<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return view('index');
});


Route::get('/table', [PokemonController::class, 'pokemonsWithTypes']);
Route::post('/store', [PokemonController::class, 'store'])->name('pokemon.store');
Route::delete('/delete/{id}', [PokemonController::class, 'destroy'])->name('pokemon.destroy');