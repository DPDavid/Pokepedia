<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pokemon;
use Illuminate\Support\Facades\File;

class PokemonSeeder extends Seeder
{
    public function run()
    {
        $basePath = database_path('pokedatabase');
        $jsonFiles = File::allFiles($basePath);

        foreach ($jsonFiles as $file) {
            if ($file->getExtension() !== 'json') continue;

            $data = json_decode(file_get_contents($file), true);
            if (json_last_error() !== JSON_ERROR_NONE) continue;

            if (isset($data[0])) {
                foreach ($data as $item) $this->processPokemon($item);
            } else {
                $this->processPokemon($data);
            }
        }
    }

    protected function processPokemon($item)
    {
        if (($item['supertype'] ?? '') !== 'Pokémon') return;

        Pokemon::updateOrCreate(
            ['pokemon_id' => $item['id']],
            [
                'name' => $item['name'],
                'supertype' => $item['supertype'],
                'level' => $item['level'] ?? null,
                'hp' => isset($item['hp']) ? intval($item['hp']) : 0,
                'evolves_from' => $item['evolvesFrom'] ?? null,
                'flavor_text' => $item['flavorText'] ?? null,
                'rarity' => $item['rarity'] ?? null,
                'national_pokedex_number' => $item['nationalPokedexNumbers'][0] ?? null,
                'image_small' => $item['images']['small'] ?? null,
                'image_large' => $item['images']['large'] ?? null,
            ]
        );
    }
}