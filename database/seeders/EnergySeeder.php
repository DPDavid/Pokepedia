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

        foreach ($jsonFiles as $file) {
            if ($file->getExtension() !== 'json') continue;

            $data = json_decode(file_get_contents($file), true);
            if (json_last_error() !== JSON_ERROR_NONE) continue;

            if (isset($data[0])) {
                foreach ($data as $item) $this->processItem($item);
            } else {
                $this->processItem($data);
            }
        }
    }

    protected function processItem($item)
    {
        if (($item['supertype'] ?? '') !== 'Energy') {
            return;
        }

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
