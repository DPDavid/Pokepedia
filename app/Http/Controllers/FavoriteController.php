<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request, $pokemonId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para guardar favoritos');
        }

        $user = Auth::user();
        $pokemon = Pokemon::where('pokemon_id', $pokemonId)->firstOrFail();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('pokemon_id', $pokemon->pokemon_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Pokémon eliminado de favoritos');
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'pokemon_id' => $pokemon->pokemon_id
            ]);
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

    public function privatePage()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $favorites = Pokemon::whereHas('favoritedBy', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->orderBy('name')
        ->paginate(12);
        
        return view('privada', compact('favorites'));
    }
}
