<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Pokemon;
use App\Models\Energy;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //Funcion para añadir los pokemons por su Id a favoritos
    public function toggleFavorite($type, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para guardar favoritos');
        }

        $user = Auth::user();
        $validTypes = ['pokemon', 'trainer', 'energy'];

        if (!in_array($type, $validTypes)) {
            return back()->with('error', 'Tipo de carta no válido');
        }

        // Verificar si la carta existe
        $card = match($type) {
            'pokemon' => Pokemon::where('pokemon_id', $id)->first(),
            'trainer' => Trainer::where('trainer_id', $id)->first(),
            'energy' => Energy::where('energy_id', $id)->first(),
        };

        if (!$card) {
            return back()->with('error', 'Carta no encontrada');
        }

        // Verificar si ya es favorito
        $favorite = Favorite::where('user_id', $user->id)
            ->where("{$type}_id", $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Eliminado de favoritos');
        }

        Favorite::create([
            'user_id' => $user->id,
            "{$type}_id" => $id
        ]);

        return back()->with('success', 'Añadido a favoritos');
    }

        public function index()
        {
            try {
                $user = Auth::user();
        
                if (!$user) {
                    dd('Usuario no autenticado');
                }
        
                // Obtener todos los favoritos directamente
                $directFavorites = $user->favorites;
                dd('Todos los favoritos:', $directFavorites);
        
                // Obtener Pokémon favoritos
                $pokemonsFavoritos = $user->favoritePokemons;
                dd('Pokémon favoritos:', $pokemonsFavoritos);
        
                // Obtener Entrenadores favoritos
                $trainersFavoritos = $user->favoriteTrainers;
                dd('Entrenadores favoritos:', $trainersFavoritos);
        
                // Obtener Energías favoritas
                $energiesFavoritas = $user->favoriteEnergies;
                dd('Energías favoritas:', $energiesFavoritas);
        
            } catch (\Exception $e) {
                dd('Error:', $e->getMessage());
            }
        }
    //Funcion para la pagina de cada usuario y sus pokemons favoritos
    public function privatePage()
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login');
        }
    
        // Obtener Pokémon favoritos
        $pokemons = Pokemon::whereHas('favorites', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
    
        // Obtener Entrenadores favoritos
        $trainers = Trainer::whereHas('favorites', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
    
        // Obtener Energías favoritas
        $energies = Energy::whereHas('favorites', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
    
        return view('privada', compact('pokemons', 'trainers', 'energies'));
    }
}
