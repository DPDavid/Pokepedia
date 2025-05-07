<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    //Definimos los campos rellenables
    protected $fillable = [
        'user_id', 
        'pokemon_id', 
        'trainer_id', 
        'energy_id'
    ];

    //Funcion relacion con los usuarios (una carta en fav pertenece a un usuario en concreto)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Funcion relacion con el modelo pokemon (una carta fav puede ser un pokemon)
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }

    //Funcion relacion con el modelo entrenador (una carta fav puede ser un entrenador)
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'trainer_id');
    }

    //Funcion relacion con el modelo energia (una carta fav puede ser una energia)
    public function energy()
    {
        return $this->belongsTo(Energy::class, 'energy_id', 'energy_id');
    }
}