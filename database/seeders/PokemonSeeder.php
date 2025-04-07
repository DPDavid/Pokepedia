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

        //Busqueda en el directorio todos los archivos
        foreach ($jsonFiles as $file) {
            if ($file->getExtension() !== 'json') continue;

            //Si uno de los archivos no es JSON lo omite
            $data = json_decode(file_get_contents($file), true);
            if (json_last_error() !== JSON_ERROR_NONE) continue;

            //Recoge el contenido del archivo y si encuentra un error lo omite
            if (isset($data[0])) {
                foreach ($data as $item) $this->processPokemon($item);
                //Si solo hay un objeto lo procesa directamente
            } else {
                $this->processPokemon($data);
            }
        }
    }

    //Funcion para procesar los pokemons
    protected function processPokemon($item)
    {
        //Condicion para que la carta sea un pokemon
        if (($item['supertype'] ?? '') !== 'Pokémon') return;

        //Crea o actualiza el pokemon en la base de datos
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
