<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FavoriteController;

Route::view("/login", "login")->name('login');
Route::view("/registro", "register")->name('registro');
Route::get("/privada", [FavoriteController::class, 'privatePage'])->middleware('auth')->name('privada');

Route::post('/validar-registro', [LoginController::class, 'register'])->name('validar-registro');
Route::post('/iniciar-sesion', [LoginController::class, 'login'])->name('iniciar-sesion');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('')->group(function () {
    Route::get('/', [PokemonController::class, 'index'])->name('pokemon.index');
    Route::get('/search', [PokemonController::class, 'search'])->name('pokemon.search');
    Route::get('/rarity/{rarity}', [PokemonController::class, 'filterByRarity'])->name('pokemon.rarity');
    Route::get('/{id}', [PokemonController::class, 'show'])->name('pokemon.show');
});

Route::middleware('auth')->group(function () {
    Route::post('/favorites/toggle/{type}/{id}', [FavoriteController::class, 'toggleFavorite'])
        ->name('favorites.toggle')
        ->where('type', 'pokemon|trainer|energy');
    
    Route::get('/privada', [FavoriteController::class, 'privatePage'])->name('privada');
});