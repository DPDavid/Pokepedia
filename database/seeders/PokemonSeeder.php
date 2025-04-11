<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Pokemon;

class PokemonSeeder extends Seeder
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
                Pokemon::updateOrCreate(
                    ['pokemon_id' => $card['id']],
                    [
                        'name' => $card['name'],
                        'supertype' => $card['supertype'],
                        'level' => $card['level'] ?? null,
                        'hp' => isset($card['hp']) ? intval($card['hp']) : 0,
                        'evolves_from' => $card['evolvesFrom'] ?? null,
                        'flavor_text' => $card['flavorText'] ?? null,
                        'rarity' => $card['rarity'] ?? null,
                        'national_pokedex_number' => $card['nationalPokedexNumbers'][0] ?? null,
                        'image_small' => $card['images']['small'] ?? null,
                        'image_large' => $card['images']['large'] ?? null,
                    ]
                );
            }

            $page++;
        } while (count($cards) > 0);
        //while ($page <= 5);
    }
}
