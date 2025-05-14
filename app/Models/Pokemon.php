<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    //Especifica el nombre de la tabla
    protected $table = 'pokemons';
    //Define la clave primaria como 'pokemon_id'
    protected $primaryKey = 'pokemon_id'; 
    //Indica que 'pokemon_id' no es autoincrementable
    public $incrementing = false; 
    // Define el tipo de la clave primaria como string
    protected $keyType = 'string'; 

    //Definimos los campos rellenables
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
        'image_large',
        'price_low',
        'price_high',
        'tcgplayer_url',
    ];
    
    //Funcion relacion con favoritos uno-muchos (una carta de pokemon puede ser marcado como fav por muchos usuarios)
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'pokemon_id', 'pokemon_id');
    }

    //Funcion relacion con las debilidades uno-muchos (una carta de pokemon puede tener varias debilidades)
    public function weaknesses()
    {
        return $this->hasMany(Weakness::class, 'pokemon_id');
    }

    //Funcion relacion con resistencias uno-mucho (una carta de pokemon puede tener varias resistencias)
    public function resistances()
    {
        return $this->hasMany(Resistances::class, 'pokemon_id');
    }

    //Funcion relacion con ataques uno-muchos (una carta de pokemon puede tenenr varios ataques)
    public function attacks()
    {
        return $this->hasMany(Attack::class, 'pokemon_id', 'pokemon_id');
    }

    //Funcion relacion con el coste de retirada uno-muchos (una carta de pokemon puede tener varios costes de retirada)
    public function retreatCosts()
    {
        return $this->hasMany(Retreat::class, 'pokemon_id', 'pokemon_id');
    }
}
