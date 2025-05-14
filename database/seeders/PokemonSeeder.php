<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Pokemon;

class PokemonSeeder extends Seeder
{
    public function run()
    {
        //Inicia la pagina en 1
        $page = 1;
        //Bucle para la API de pokemon, el maximo permitido de cartas son 250 por pagina
        do {
            $response = Http::get("https://api.pokemontcg.io/v2/cards", [
                'page' => $page,
                'pageSize' => 250,
                //Filtro para que solo coja las que tenga la etiqueta pokemon
                'q' => 'supertype:Pokémon',
            ]);

            //Extrae el array de las cartas
            $cards = $response->json()['data'] ?? [];

            //Por cada carta la guarda en la base de datos o la actualiza
            foreach ($cards as $card) {
                $prices = $card['tcgplayer']['prices']['holofoil'] ?? null;
                $price_low = isset($prices['low']) ? (float) $prices['low'] : null;
                $price_high = isset($prices['high']) ? (float) $prices['high'] : null;
                $url = $card['tcgplayer']['url'] ?? null;

                $pokemon = Pokemon::updateOrCreate(
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
                        'price_low' => isset($prices['low']) ? (float) $prices['low'] : null,
                        'price_high' => isset($prices['high']) ? (float) $prices['high'] : null,
                        'tcgplayer_url' => $url,
                    ]
                );
            }
            //Incrementa el numero de la pagina para las siguientes 250 cartas
            $page++;
        }
        //El bucle continua hasta que no haya ninguna carta
        while (count($cards) > 0);
        //while ($page <= 5);
    }
}
