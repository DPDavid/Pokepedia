<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    //Definimos los campos rellenables
    protected $fillable = [
        'trainer_id',
        'name',
        'supertype',
        'subtypes',
        'rules',
        'number',
        'artist',
        'rarity',
        'legalities',
        'image_small',
        'image_large'
    ];

    //Convierte los campos en arrays
    protected $casts = [
        'subtypes' => 'array',
        'rules' => 'array',
        'legalities' => 'array'
    ];

    //Funcion relacion con favoritos uno-muchos (una carta de entrenador puede ser fav de varios usuarios)
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'trainer_id', 'trainer_id');
    }
}
