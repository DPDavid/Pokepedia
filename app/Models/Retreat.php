<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retreat extends Model
{
    protected $table = 'retreat_costs';
    use HasFactory;

    //Campos rellenables
    protected $fillable = [
        'pokemon_id',
        'type',
    ];

    //Funcion relacion con el modelo pokemon
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }
}
