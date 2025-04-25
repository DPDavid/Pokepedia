<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Trainer;

class TrainerSeeder extends Seeder
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
                //Filtro para que solo coja las que tenga la etiqueta trainer
                'q' => 'supertype:Trainer',
            ]);

            //Extrae el array de las cartas
            $cards = $response->json()['data'] ?? [];

            //Por cada carta la guarda en la base de datos o la actualiza
            foreach ($cards as $card) {
                Trainer::updateOrCreate(
                    ['trainer_id' => $card['id']],
                    [
                        'name' => $card['name'],
                        'supertype' => $card['supertype'],
                        'subtypes' => $card['subtypes'] ?? [],
                        'rules' => $card['rules'] ?? [],
                        'number' => $card['number'] ?? null,
                        'artist' => $card['artist'] ?? 'Unknown Artist',
                        'rarity' => $card['rarity'] ?? null,
                        'legalities' => $card['legalities'] ?? [],
                        'image_small' => $card['images']['small'] ?? null,
                        'image_large' => $card['images']['large'] ?? null,
                    ]
                );
            }
            //Incrementa el numero de la pagina para las siguientes 250 cartas
            $page++;
        }
        //El bucle continua hasta que no haya ninguna carta 
        while (count($cards) > 0);
    }
}
