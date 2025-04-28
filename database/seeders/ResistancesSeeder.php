<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Pokemon;

class ResistancesSeeder extends Seeder
{
    public function run()
    {
        // Inicia la página en 1
        $page = 1;
    
        // Bucle para la API de Pokémon, el máximo permitido de cartas son 250 por página
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
                // Verifica si la carta tiene resistencias
                if (!empty($card['resistances'])) {
                    // Encuentra el Pokémon por su pokemon_id
                    $pokemon = Pokemon::where('pokemon_id', $card['id'])->first();
    
                    if ($pokemon) {
                        // Borra las resistencias anteriores para ese Pokémon
                        $pokemon->resistances()->delete();
    
                        // Inserta las nuevas resistencias
                        foreach ($card['resistances'] as $resistanceData) {
                            $pokemon->resistances()->create([
                                'type' => $resistanceData['type'] ?? 'Unknown',
                                'value' => $resistanceData['value'] ?? 'N/A',
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
