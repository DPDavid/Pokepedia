<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Pokemon;

class WeaknessSeeder extends Seeder
{
    public function run()
    {
        // Inicia la pagina en 1
        $page = 1;

        // Bucle para la API de pokemon, el máximo permitido de cartas son 250 por pagina
        do {
            $response = Http::get("https://api.pokemontcg.io/v2/cards", [
                'page' => $page,
                'pageSize' => 250,
                'q' => 'supertype:Pokémon',
            ]);

            // Extrae el array de las cartas
            $cards = $response->json()['data'] ?? [];

            // Por cada carta la guarda en la base de datos o la actualiza
            foreach ($cards as $card) {
                // Verifica si la carta tiene debilidades
                if (!empty($card['weaknesses'])) {
                    // Encuentra el Pokémon por su pokemon_id
                    $pokemon = Pokemon::where('pokemon_id', $card['id'])->first();

                    if ($pokemon) {
                        // Borra las debilidades anteriores para ese Pokémon
                        $pokemon->weaknesses()->delete();

                        // Inserta las nuevas debilidades
                        foreach ($card['weaknesses'] as $weaknessData) {
                            $pokemon->weaknesses()->create([
                                'type' => $weaknessData['type'] ?? 'Unknown',
                                'value' => $weaknessData['value'] ?? 'N/A',
                                // Usamos el ID del Pokémon
                                'pokemon_id' => $pokemon->id,
                            ]);
                        }
                    }
                }
            }

            // Incrementa el número de la página para las siguientes 250 cartas
            $page++;
        } while (count($cards) > 0);
    }
}
