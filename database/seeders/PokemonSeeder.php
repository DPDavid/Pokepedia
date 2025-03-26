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

        //Obtener todos los archivos JSON
        $jsonFiles = File::allFiles($basePath);

        $totalProcessed = 0;
        $totalSkipped = 0;

        foreach ($jsonFiles as $file) {
            //Condicion para los archivos JSON
            if ($file->getExtension() !== 'json') {
                $totalSkipped++;
                continue;
            }

            $pokemonData = json_decode(file_get_contents($file), true);

            //Validacion del archivo
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->command->warn("⏩ Archivo inválido: {$file->getFilename()}");
                $totalSkipped++;
                continue;
            }

            //Array de pokemon
            if (isset($pokemonData[0]) && is_array($pokemonData[0])) {
                foreach ($pokemonData as $pokemon) {
                    $this->processPokemon($pokemon, $totalProcessed, $totalSkipped);
                }
            } 
            //Pokemon individual
            else {
                $this->processPokemon($pokemonData, $totalProcessed, $totalSkipped);
            }
        }

        $this->command->info("✅ Proceso completado");
        $this->command->info("Pokémon procesados: {$totalProcessed}");
        $this->command->warn("Pokémon omitidos: {$totalSkipped}");
    }

    protected function processPokemon($pokemon, &$processed, &$skipped)
    {
        //Validacion que sea un pokemon
        if (!isset($pokemon['supertype']) || $pokemon['supertype'] !== 'Pokémon') {
            $skipped++;
            return;
        }

        try {
            //Creacion del pokemon
            Pokemon::updateOrCreate(
                ['pokemon_id' => $pokemon['id']],
                [
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
                ]
            );
            $processed++;
        } catch (\Exception $e) {
        }
    }
}