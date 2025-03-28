<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //Funcion para añadir los pokemons por su Id a favoritos
    public function toggleFavorite(Request $request, $pokemonId)
    {
        //Verificacion de que el usuario ha iniciado sesion para añadir a favoritos
        if (!Auth::check()) {
            //Si no ha iniciado sesion lo redirige a la pagina del login para que lo haga
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para guardar favoritos');
        }

        $user = Auth::user();
        //Busqueda del pokemon por su id si no lo encuentra lanza un error
        $pokemon = Pokemon::where('pokemon_id', $pokemonId)->firstOrFail();

        //Verificacion si el pokemon ya esta en favoritos
        $favorite = Favorite::where('user_id', $user->id)
            ->where('pokemon_id', $pokemon->pokemon_id)
            ->first();

        //Si ya esta agregado a favorito lo elimina y si no lo esta lo agrega a la tabla de favoritos
        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Pokémon eliminado de favoritos');
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'pokemon_id' => $pokemon->pokemon_id
            ]);
            //Vuelve a la página anterior con un mensaje de que se ha añadido correctamente a favoritos
            return back()->with('success', 'Pokémon añadido a favoritos');
        }
    }

    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                dd('Usuario no autenticado');
            }

            $directFavorites = $user->favorites;
            dd($directFavorites);

            $pokemonsFavoritos = $user->favoritePokemons;
            dd($pokemonsFavoritos);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    //Funcion para la pagina de cada usuario y sus pokemons favoritos
    public function privatePage()
    {
        //Verficacion del usuario, si no lo esta se redirige al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        //Obtiene los pokemons favoritos de cada usuario por la id del usuario
        $favorites = Pokemon::whereHas('favoritedBy', function($query) {
            $query->where('user_id', Auth::id());
        })
        //Los ordena por nombre y de 12 en 12
        ->orderBy('name')
        ->paginate(12);
        
        //retorna a la vista enviando la variable de favoritos
        return view('privada', compact('favorites'));
    }
}
