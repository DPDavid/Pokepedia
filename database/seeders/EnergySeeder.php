<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Energy;

class EnergySeeder extends Seeder
{
    public function run()
    {
        $page = 1;

        do {
            $response = Http::get("https://api.pokemontcg.io/v2/cards", [
                'page' => $page,
                'pageSize' => 250,
                'q' => 'supertype:Energy',
            ]);

            $cards = $response->json()['data'] ?? [];

            foreach ($cards as $card) {
                Energy::updateOrCreate(
                    ['energy_id' => $card['id']],
                    [
                        'name' => $card['name'],
                        'supertype' => $card['supertype'],
                        'subtypes' => $card['subtypes'] ?? [],
                        'number' => $card['number'] ?? null,
                        'artist' => $card['artist'] ?? 'Unknown Artist',
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
