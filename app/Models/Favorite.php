<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pokemon_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id', 'pokemon_id');
    }
}