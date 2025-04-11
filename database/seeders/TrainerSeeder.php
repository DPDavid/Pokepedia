<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Trainer;

class TrainerSeeder extends Seeder
{
    public function run()
    {
        $page = 1;

        do {
            $response = Http::get("https://api.pokemontcg.io/v2/cards", [
                'page' => $page,
                'pageSize' => 250,
                'q' => 'supertype:Trainer',
            ]);

            $cards = $response->json()['data'] ?? [];

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

            $page++;
        } while (count($cards) > 0);
    }
}
