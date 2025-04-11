<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Http::macro('pokemonTCG', function () {
            return Http::withHeaders([
                'X-Api-Key' => env('c4e9e44c-c2b4-4c0f-84a7-a02b5a7e3f65')
            ])->baseUrl('https://api.pokemontcg.io/v2');
        });
    }
}
