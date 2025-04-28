<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resistances extends Model
{
    use HasFactory;

    // El nombre de la tabla asociada al modelo
    protected $table = 'resistances';

    // Los atributos que son asignables masivamente
    protected $fillable = [
        'pokemon_id',
        'type',
        'value',
    ];

    // La relación con el modelo Pokemon
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }
    
    protected $casts = [
        'retreatCost' => 'array', 
    ];
}
