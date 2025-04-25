<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Funcion relacion con favoritos uno-muchos
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    //Funcion relacion con pokemons ninguno-muchos
    public function favoritePokemons()
    {
        return $this->belongsToMany(Pokemon::class, 'favorites', 'user_id', 'pokemon_id')
            ->withTimestamps();
    }

    //Funcion relacion con entrenadores ninguno-muchos
    public function favoriteTrainers()
    {
        return $this->belongsToMany(Trainer::class, 'favorites', 'user_id', 'trainer_id')
            ->withTimestamps();
    }

    //Funcion relacion con energias ninguno-muchos
    public function favoriteEnergies()
    {
        return $this->belongsToMany(Energy::class, 'favorites', 'user_id', 'energy_id')
            ->withTimestamps();
    }
}
