<?php

namespace Database\Seeders;

use App\Models\Trainer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TrainerSeeder extends Seeder
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
                foreach ($data as $item) $this->processTrainer($item);
            } else {
                $this->processTrainer($data);
            }
        }
    }

    //Funcion para procesar los entrenadores
    protected function processTrainer($item)
    {
        //Condicion para que la carta sea un entrenador
        if (($item['supertype'] ?? '') !== 'Trainer') return;
        
        //Establece unos valores por defecto (evita errores si falta algun dato en la base de datos)
        $defaults = [
            'id' => null,
            'name' => 'Unknown',
            'number' => '000',
            'artist' => 'Unknown Artist',
            'legalities' => [],
            'subtypes' => [],
            'rules' => [],
            'images' => [
                'small' => null,
                'large' => null
            ]
        ];
        $item = array_merge($defaults, $item);

        //Crea o actualiza la energia en la base de datos
        Trainer::updateOrCreate(
            ['trainer_id' => $item['id']],
            [
                'name' => $item['name'],
                'supertype' => $item['supertype'],
                'subtypes' => json_encode($item['subtypes']),
                'rules' => json_encode($item['rules']),
                'number' => $item['number'],
                'artist' => $item['artist'],
                'rarity' => $item['rarity'] ?? null,
                'legalities' => json_encode($item['legalities']),
                'image_small' => $item['images']['small'],
                'image_large' => $item['images']['small'],
            ]
        );
    }
}
