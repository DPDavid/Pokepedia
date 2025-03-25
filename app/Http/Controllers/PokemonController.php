<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function index()
    {
        $pokemons = Pokemon::orderBy('name')->paginate(20);
        return view('pokemon.index', compact('pokemons'));
    }

    public function show($id)
    {
        $pokemon = Pokemon::where('pokemon_id', $id)->firstOrFail();
        return view('pokemon.show', compact('pokemon'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        
        $pokemons = Pokemon::where('name', 'LIKE', "%{$searchTerm}%")
                           ->paginate(20)
                           ->appends(['search' => $searchTerm]);

        return view('pokemon.index', compact('pokemons', 'searchTerm'));
    }

    public function filterByRarity($rarity)
    {
        $pokemons = Pokemon::where('rarity', $rarity)
                          ->paginate(20);

        return view('pokemon.index', compact('pokemons', 'rarity'));
    }
}