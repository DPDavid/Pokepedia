<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Energy extends Model
{
    use HasFactory;

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

    protected $casts = [
        'subtypes' => 'array',
        'legalities' => 'array'
    ];
    
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'energy_id', 'energy_id');
    }
}
