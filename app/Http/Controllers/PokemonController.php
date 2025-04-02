<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\Trainer;
use App\Models\Energy;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $rarity = $request->input('rarity');

        // Get all cards in one collection
        $cards = collect();

        // Get Pokémon cards
        $pokemons = Pokemon::query();
        if ($query) {
            $pokemons->where('name', 'LIKE', "%{$query}%");
        }
        if ($rarity) {
            $pokemons->where('rarity', $rarity);
        }
        $pokemons = $pokemons->orderBy('name', 'asc')->get();
        $pokemons->each(function ($item) use (&$cards) {
            $item->card_type = 'pokemon';
            $cards->push($item);
        });

        // Get Trainer cards
        $trainers = Trainer::query();
        if ($query) {
            $trainers->where('name', 'LIKE', "%{$query}%");
        }
        if ($rarity) {
            $trainers->where('rarity', $rarity);
        }
        $trainers = $trainers->orderBy('name', 'asc')->get();
        $trainers->each(function ($item) use (&$cards) {
            $item->card_type = 'trainer';
            $cards->push($item);
        });

        // Get Energy cards
        $energies = Energy::query();
        if ($query) {
            $energies->where('name', 'LIKE', "%{$query}%");
        }
        $energies = $energies->orderBy('name', 'asc')->get();
        $energies->each(function ($item) use (&$cards) {
            $item->card_type = 'energy';
            $cards->push($item);
        });

        //Paginado de la coleccion de cartas
        $page = $request->input('page', 1);
        $perPage = 12;
        $paginatedCards = new \Illuminate\Pagination\LengthAwarePaginator(
            $cards->forPage($page, $perPage),
            $cards->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('pokemon.index', [
            'cards' => $paginatedCards,
            'searchTerm' => $query,
            'currentRarity' => $rarity
        ]);
    }

    public function show($id)
    {
        // Primero intenta encontrar como Pokémon
        $card = Pokemon::where('pokemon_id', $id)->first();
        $type = 'pokemon';

        if (!$card) {
            $card = Trainer::where('trainer_id', $id)->first();
            $type = 'trainer';
        }

        if (!$card) {
            $card = Energy::where('energy_id', $id)->firstOrFail();
            $type = 'energy';
        }

        return view('pokemon.show', compact('card', 'type'));
    }
}
