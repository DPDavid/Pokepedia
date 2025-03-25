<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = 'pokemons';
    protected $fillable = [
        'pokemon_id', 'name', 'supertype', 'level', 'hp',
        'evolves_from', 'flavor_text', 'rarity',
        'national_pokedex_number', 'image_small', 'image_large'
    ];
}