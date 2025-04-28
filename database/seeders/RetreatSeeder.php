<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Pokemon;
use App\Models\Retreat;

class RetreatSeeder extends Seeder
{
    public function run()
    {
        $page = 1;

        do {
            $response = Http::get("https://api.pokemontcg.io/v2/cards", [
                'page' => $page,
                'pageSize' => 250,
                'q' => 'supertype:Pokémon',
            ]);

            $cards = $response->json()['data'] ?? [];

            foreach ($cards as $card) {
                if (!empty($card['retreatCost'])) {
                    $pokemon = Pokemon::where('pokemon_id', $card['id'])->first();

                    if ($pokemon) {
                        // Primero elimina costes antiguos para evitar duplicados
                        $pokemon->retreatCosts()->delete();

                        foreach ($card['retreatCost'] as $type) {
                            Retreat::create([
                                'pokemon_id' => $card['id'],
                                'type' => $type,
                            ]);
                        }
                    }
                }
            }

            $page++;
        } while (count($cards) > 0);
    }
}
