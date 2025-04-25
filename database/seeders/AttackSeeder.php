<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Attack;

class AttackSeeder extends Seeder
{
    public function run()
    {
        //Inicia la página en 1
        $page = 1;
        //Bucle para la API de Pokémon, el máximo permitido de cartas son 250 por página
        do {
            $response = Http::get("https://api.pokemontcg.io/v2/cards", [
                'page' => $page,
                'pageSize' => 250,
                //Filtro para que solo coja las que tengan la etiqueta "Pokémon"
                'q' => 'supertype:Pokémon',
            ]);

            //Extrae el array de las cartas
            $cards = $response->json()['data'] ?? [];

            //Recorre cada carta y guarda los ataques
            foreach ($cards as $card) {
                //Si la carta tiene ataques, los guarda
                if (!empty($card['attacks'])) {
                    foreach ($card['attacks'] as $attackData) {
                        Attack::updateOrCreate(
                            [
                                'pokemon_id' => $card['id'], 
                                'name' => $attackData['name'],
                                'converted_energy_cost' => $attackData['convertedEnergyCost'] ?? null,
                            ],
                            [
                                'damage' => $attackData['damage'] ?? null,
                                'text' => $attackData['text'] ?? null,
                                'cost' => json_encode($attackData['cost'] ?? []), 
                            ]
                        );
                    }
                }
            }

            //Incrementa el número de la página para las siguientes 250 cartas
            $page++;
        }
        //El bucle continúa hasta que no haya cartas
        while (count($cards) > 0);
    }
}
