<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Pokemon;
use App\Models\Energy;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //------------Funcion para añadir los pokemons a favoritos------------
    public function toggleFavorite($type, $id)
    {
        //Verificacion si se ha iniciado sesion
        if (!Auth::check()) {
            //Si el usuario no ha iniciado sesion lo devuelve al login
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para guardar favoritos');
        }

        //Obtencion del usuario
        $user = Auth::user();
        //Tipo de carta permitido
        $validTypes = ['pokemon', 'trainer', 'energy'];

        //Validacion de la carta
        if (!in_array($type, $validTypes)) {
            //Si no es valido devuelve un error
            return back()->with('error', 'Tipo de carta no válido');
        }

        //Busqueda de la carta de cada tipo
        $card = match ($type) {
            'pokemon' => Pokemon::where('pokemon_id', $id)->first(),
            'trainer' => Trainer::where('trainer_id', $id)->first(),
            'energy' => Energy::where('energy_id', $id)->first(),
        };

        //Si el tipo de carta no se encuentra devuelve un error
        if (!$card) {
            return back()->with('error', 'Carta no encontrada');
        }

        //Verificar si ya se ha añadido a favorito
        $favorite = Favorite::where('user_id', $user->id)
            ->where("{$type}_id", $id)
            ->first();

        //Si ya esta en favorito es eliminada, sirve para alterna las cartas en favoritos
        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Eliminado de favoritos');
        }

        //Si no esta en favoritos crea la carta en la tabla favoritos
        Favorite::create([
            'user_id' => $user->id,
            "{$type}_id" => $id
        ]);

        return back()->with('success', 'Añadido a favoritos');
    }

    //------------Funcion para las cartas favoritas de cada usuario------------
    public function privatePage()
    {
        //Obtencion del usuario
        $user = Auth::user();

        //Verificacion inicio de sesion
        if (!$user) {
            //Si no esta logeado lo manda para que lo haga
            return redirect()->route('login');
        }

        //Busca los pokemons que el usuario ha marcado como favoritos
        $pokemons = Pokemon::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        //Busca los entrenadores que el usuario ha marcado como favoritos
        $trainers = Trainer::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        //Busca las energias que el usuario ha marcado como favoritos
        $energies = Energy::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        //Retorna a la vista con las cartas favoritas del usuario
        return view('privada', compact('pokemons', 'trainers', 'energies'));
    }
}
