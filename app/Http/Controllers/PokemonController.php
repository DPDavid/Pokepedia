<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\Trainer;
use App\Models\Energy;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    //Funcion para mostrar las cartas en la página
    public function index(Request $request)
    {
        $query = $request->input('search');
        $rarity = $request->input('rarity');
        $typeFilter = $request->input('type');
        $orderBy = $request->input('order_by', 'name');
        $direction = $request->input('direction', 'asc');

        $cards = collect();

        // ------------ Pokémons ------------
        $pokemons = Pokemon::query();
        if ($query) {
            $pokemons->where('name', 'LIKE', "%{$query}%");
        }
        if ($rarity) {
            $pokemons->where('rarity', $rarity);
        }
        if ($typeFilter) {
            $pokemons->where('type', $typeFilter);
        }
        if ($orderBy === 'national_pokedex_number') {
            $pokemons->whereNotNull('national_pokedex_number');
        }

        if (in_array($orderBy, ['name', 'hp', 'national_pokedex_number'])) {
            $pokemons->orderBy($orderBy, $direction);
        }

        $pokemons = $pokemons->get();
        $pokemons->each(function ($item) use (&$cards) {
            $item->card_type = 'pokemon';
            $cards->push($item);
        });

        // ------------ Trainers y Energías ------------
        // Solo incluir si NO hay filtro por tipo
        if (!$typeFilter) {
            // ------------ Trainers ------------
            $trainers = Trainer::query();
            if ($query) {
                $trainers->where('name', 'LIKE', "%{$query}%");
            }
            if ($rarity) {
                $trainers->where('rarity', $rarity);
            }
            if ($orderBy === 'name') {
                $trainers->orderBy('name', $direction);
            }
            $trainers = $trainers->get();
            $trainers->each(function ($item) use (&$cards) {
                $item->card_type = 'trainer';
                $cards->push($item);
            });

            // ------------ Energías ------------
            $energies = Energy::query();
            if ($query) {
                $energies->where('name', 'LIKE', "%{$query}%");
            }
            if ($orderBy === 'name') {
                $energies->orderBy('name', $direction);
            }
            $energies = $energies->get();
            $energies->each(function ($item) use (&$cards) {
                $item->card_type = 'energy';
                $cards->push($item);
            });
        }

        // Resto del código de paginación...
        $page = $request->input('page', 1);
        $perPage = 12;
        $paginatedCards = new \Illuminate\Pagination\LengthAwarePaginator(
            $cards->forPage($page, $perPage),
            $cards->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        return view('pokemon.index', [
            'cards' => $paginatedCards,
            'searchTerm' => $query,
            'currentRarity' => $rarity,
            'selectedType' => $typeFilter,
            'currentOrderBy' => $orderBy,
            'currentDirection' => $direction,
        ]);
    }
    //Funcion para mostrar los detalles de una carta
    public function show($id)
    {
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
