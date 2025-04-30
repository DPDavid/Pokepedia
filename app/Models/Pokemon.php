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

    //Campos que se pueden rellenar
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

    //Funcion relacion con favoritos uno-muchos
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'pokemon_id', 'pokemon_id');
    }

    //Funcion relacion con usuarios ninguno-muchos
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'pokemon_id', 'user_id')
            ->withTimestamps();
    }

    //Funcion relacion con las debilidades uno-muchos
    public function weaknesses()
    {
        return $this->hasMany(Weakness::class, 'pokemon_id');
    }

    //Funcion relacion con resistencias uno-mucho
    public function resistances()
    {
        return $this->hasMany(Resistances::class, 'pokemon_id');
    }

    //Funcion relacion con ataques uno-muchos
    public function attacks()
    {
        return $this->hasMany(Attack::class, 'pokemon_id', 'pokemon_id');
    }

    //Funcion relacion con el coste de retirada uno-muchos
    public function retreatCosts()
    {
        return $this->hasMany(Retreat::class, 'pokemon_id', 'pokemon_id');
    }
}
