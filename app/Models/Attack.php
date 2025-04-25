<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attack extends Model
{
    use HasFactory;

    protected $fillable = [
        'pokemon_id',
        'name',
        'cost',
        'converted_energy_cost',
        'damage',
        'text',
    ];

    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }
}