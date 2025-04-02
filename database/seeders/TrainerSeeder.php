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
        if (($item['supertype'] ?? '') !== 'Trainer') return;
        
        $defaults = [
            'id' => null,
            'name' => 'Unknown',
            'number' => '000',
            'artist' => 'Unknown Artist',
            'legalities' => [],
            'subtypes' => [],
            'rules' => []
        ];
    
        $item = array_merge($defaults, $item);

        $images = $item['images'] ?? [
            'small' => null,
            'large' => null
        ];

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
                'image_small' => $images['small'],
                'image_large' => $images['large'],
            ]
        );
    }
}
