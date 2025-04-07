<?php

namespace Database\Seeders;

use App\Models\Energy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EnergySeeder extends Seeder
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
                foreach ($data as $item) $this->processEnergy($item);
                //Si solo hay un objeto lo procesa directamente
            } else {
                $this->processEnergy($data);
            }
        }
    }

    //Funcion para procesar las energias
    protected function processEnergy($item)
    {
        //Condicion para que la carta sea una energia
        if (($item['supertype'] ?? '') !== 'Energy') {
            return;
        }

        //Establece unos valores por defecto (evita errores si falta algun dato en la base de datos)
        $defaultValues = [
            'subtypes' => [],
            'legalities' => [],
            'artist' => 'Unknown Artist',
            'images' => [
                'small' => null,
                'large' => null
            ]
        ];
        $item = array_merge($defaultValues, $item);

        //Crea o actualiza la energia en la base de datos
        Energy::updateOrCreate(
            ['energy_id' => $item['id']],
            [
                'name' => $item['name'],
                'supertype' => $item['supertype'],
                'subtypes' => json_encode($item['subtypes']),
                'number' => $item['number'],
                'artist' => $item['artist'],
                'legalities' => json_encode($item['legalities']),
                'image_small' => $item['images']['small'],
                'image_large' => $item['images']['large'],
            ]
        );
    }
}
