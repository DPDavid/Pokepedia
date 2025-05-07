<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retreat extends Model
{
    use HasFactory;

    //El nombre de la tabla asociada al modelo
    protected $table = 'retreat_costs';

    //Definimos los campos rellenables
    protected $fillable = [
        'pokemon_id',
        'type',
    ];

    //Funcion relacion con el modelo pokemon muchos-uno (los coste de retirada estan asociadas a una carta de pokemon) 
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }
}
