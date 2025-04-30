<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resistances extends Model
{
    use HasFactory;

    //El nombre de la tabla asociada al modelo
    protected $table = 'resistances';

    //Campos que se pueden rellenar
    protected $fillable = [
        'pokemon_id',
        'type',
        'value',
    ];

    //Funcion relacion con el modelo pokemon
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }
    
    protected $casts = [
        'retreatCost' => 'array', 
    ];
}
