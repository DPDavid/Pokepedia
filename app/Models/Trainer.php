<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

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

    protected $casts = [
        'subtypes' => 'array',
        'rules' => 'array',
        'legalities' => 'array'
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'trainer_id', 'trainer_id');
    }
}
