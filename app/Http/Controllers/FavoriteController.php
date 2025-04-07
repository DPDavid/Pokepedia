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
        //Verificacion si se ha iniciado sesion
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para guardar favoritos');
        }

        $user = Auth::user();
        //Tipo de carta permitido
        $validTypes = ['pokemon', 'trainer', 'energy'];

        //Validacion de la carta
        if (!in_array($type, $validTypes)) {
            return back()->with('error', 'Tipo de carta no válido');
        }

        //Busqueda de la carta de cada tipo
        $card = match ($type) {
            'pokemon' => Pokemon::where('pokemon_id', $id)->first(),
            'trainer' => Trainer::where('trainer_id', $id)->first(),
            'energy' => Energy::where('energy_id', $id)->first(),
        };

        //Error de que el tipo de la carta no se ha encontrado
        if (!$card) {
            return back()->with('error', 'Carta no encontrada');
        }

        //Verificar si ya se ha añadido a favorito
        $favorite = Favorite::where('user_id', $user->id)
            ->where("{$type}_id", $id)
            ->first();

        //Si ya esta en favorito y es pulsado de nuevo la elimina
        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Eliminado de favoritos');
        }

        //Si no esta en favoritos  crea la carta en la tabla favoritos
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
            
            //Verificacion inicio de sesion
            if (!$user) {
                dd('Usuario no autenticado');
            }

            //Obtener todos los favoritos directamente
            $directFavorites = $user->favorites;
            dd('Todos los favoritos:', $directFavorites);

            //Obtener Pokémon favoritos
            $pokemonsFavoritos = $user->favoritePokemons;
            dd('Pokémon favoritos:', $pokemonsFavoritos);

            //Obtener Entrenadores favoritos
            $trainersFavoritos = $user->favoriteTrainers;
            dd('Entrenadores favoritos:', $trainersFavoritos);

            //Obtener Energías favoritas
            $energiesFavoritas = $user->favoriteEnergies;
            dd('Energías favoritas:', $energiesFavoritas);
        } catch (\Exception $e) {
            dd('Error:', $e->getMessage());
        }
    }

    //Funcion para la pagina de cada usuario y sus cartas favoritas
    public function privatePage()
    {
        $user = Auth::user();

        //Verificacion inicio de sesion
        if (!$user) {
            return redirect()->route('login');
        }

        //Obtener Pokémon favoritos
        $pokemons = Pokemon::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        //Obtener Entrenadores favoritos
        $trainers = Trainer::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        //Obtener Energías favoritas
        $energies = Energy::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        //Retorna a la vista de la cuenta las cartas que se hayan añadido
        return view('privada', compact('pokemons', 'trainers', 'energies'));
    }
}
