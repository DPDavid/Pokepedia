<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weakness extends Model
{
    use HasFactory;

   //Definimos los campos rellenable
    protected $fillable = [
        'pokemon_id',
        'type',
        'value',
    ];

    //Funcion relacion con el modelo pokemon muchos-uno (las debilidades estan asociadas a una carta pokemon)
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id');
    }
}
