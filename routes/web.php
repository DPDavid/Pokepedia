<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::prefix('pokemon')->group(function () {
    Route::get('/', [PokemonController::class, 'index'])->name('pokemon.index');
    Route::get('/search', [PokemonController::class, 'search'])->name('pokemon.search');
    Route::get('/rarity/{rarity}', [PokemonController::class, 'filterByRarity'])->name('pokemon.rarity');
    Route::get('/{id}', [PokemonController::class, 'show'])->name('pokemon.show');
});


