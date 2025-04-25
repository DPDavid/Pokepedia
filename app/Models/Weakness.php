<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weakness extends Model
{
    use HasFactory;

    protected $fillable = [
        'pokemon_id',
        'type',
        'value',
    ];

    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id');
    }
}
