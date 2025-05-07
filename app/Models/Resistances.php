<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resistances extends Model
{
    use HasFactory;

    //El nombre de la tabla asociada al modelo
    protected $table = 'resistances';

    //Definimos los campos rellenables
    protected $fillable = [
        'pokemon_id',
        'type',
        'value',
    ];

    //Funcion relacion con el modelo pokemon muchos-uno (las resistencias estan asociada a una carta de pokemon)
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }
    
    //Convierte los campos a un array para almacenar los valores
    protected $casts = [
        'retreatCost' => 'array', 
    ];
}
