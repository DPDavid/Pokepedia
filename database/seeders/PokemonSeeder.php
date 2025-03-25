<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pokemon;

class PokemonSeeder extends Seeder
{
    public function run()
{
    $json = file_get_contents(database_path('pokedatabase/base1.json'));
    $pokemons = json_decode($json, true);

    foreach ($pokemons as $pokemon) {
        if ($pokemon['supertype'] !== 'Pokémon') {
            continue;
        }

        if (!isset($pokemon['hp'])) {
            continue; 
        }

        Pokemon::create([
            'pokemon_id' => $pokemon['id'],
            'name' => $pokemon['name'],
            'supertype' => $pokemon['supertype'],
            'level' => $pokemon['level'] ?? null,
            'hp' => isset($pokemon['hp']) ? intval($pokemon['hp']) : 0,
            'evolves_from' => $pokemon['evolvesFrom'] ?? null,
            'flavor_text' => $pokemon['flavorText'] ?? null,
            'rarity' => $pokemon['rarity'] ?? null,
            'national_pokedex_number' => $pokemon['nationalPokedexNumbers'][0] ?? null,
            'image_small' => $pokemon['images']['small'] ?? null,
            'image_large' => $pokemon['images']['large'] ?? null,
        ]);
    }
}
}
