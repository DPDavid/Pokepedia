<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = 'pokemons';
    protected $primaryKey = 'pokemon_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pokemon_id',
        'name',
        'supertype',
        'level',
        'hp',
        'evolves_from',
        'flavor_text',
        'rarity',
        'national_pokedex_number',
        'image_small',
        'image_large'
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'pokemon_id', 'pokemon_id');
    }   

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'pokemon_id', 'user_id')
            ->withTimestamps();
    }
}
