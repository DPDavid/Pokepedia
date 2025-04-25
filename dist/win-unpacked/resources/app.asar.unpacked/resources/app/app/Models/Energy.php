<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Energy extends Model
{
    use HasFactory;

    //Campos que se pueden rellenar
    protected $fillable = [
        'energy_id',
        'name',
        'supertype',
        'subtypes',
        'number',
        'artist',
        'legalities',
        'image_small',
        'image_large'
    ];

    //Convierte los campos en arrays
    protected $casts = [
        'subtypes' => 'array',
        'legalities' => 'array'
    ];
    
    //Funcion relacion uno muchos entre energia y favoritos (una carta puede tener muchos favoritos)
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'energy_id', 'energy_id');
    }
}
