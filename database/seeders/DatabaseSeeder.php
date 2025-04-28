<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PokemonSeeder::class,
            TrainerSeeder::class,
            EnergySeeder::class,
            AttackSeeder::class,
            WeaknessSeeder::class,
            ResistancesSeeder::class,
            RetreatSeeder::class,
        ]);
    }
}
