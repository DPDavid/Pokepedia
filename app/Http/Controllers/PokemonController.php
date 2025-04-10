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
        //Obtencion de la busqueda y el filtro de rareza de la carta
        $query = $request->input('search');
        $rarity = $request->input('rarity');

        //Creacion de una coleccion de cartas para que esten todas agrupadas
        $cards = collect();

        //Filtro de los pokemons
        $pokemons = Pokemon::query();
        //Primer filtro por nombre en el buscador
        if ($query) {
            $pokemons->where('name', 'LIKE', "%{$query}%");
        }
        //Segundo filtro por rareza de las cartas
        if ($rarity) {
            $pokemons->where('rarity', $rarity);
        }
        //Ordena por nombre de manera ascendente (Por ahora) 
        $pokemons = $pokemons->orderBy('name', 'asc')->get();
        //Se le agrega un atributo especial (pokemon) y se añade a la coleccion
        $pokemons->each(function ($item) use (&$cards) {
            $item->card_type = 'pokemon';
            $cards->push($item);
        });

        //Filtro de los entrenadores (consumibles)
        $trainers = Trainer::query();
        //Primer filtro por nombre en el buscador
        if ($query) {
            $trainers->where('name', 'LIKE', "%{$query}%");
        }
        //Segundo filtro por rareza de las cartas
        if ($rarity) {
            $trainers->where('rarity', $rarity);
        }
        //Ordena por nombre de manera ascendente (Por ahora) 
        $trainers = $trainers->orderBy('name', 'asc')->get();

        //Se le agrega un atributo especial (trainer) y se añade a la coleccion
        $trainers->each(function ($item) use (&$cards) {
            $item->card_type = 'trainer';
            $cards->push($item);
        });


        //Filtro de las energias
        $energies = Energy::query();
        //Filtro por nombre en el buscador
        if ($query) {
            $energies->where('name', 'LIKE', "%{$query}%");
        }
        //Ordena por nombre de manera ascendente (Por ahora) 
        $energies = $energies->orderBy('name', 'asc')->get();
        //Se le agrega un atributo especial (energy) y se añade a la coleccion
        $energies->each(function ($item) use (&$cards) {
            $item->card_type = 'energy';
            $cards->push($item);
        });

        //Paginacion de la cartas
        $page = $request->input('page', 1);
        $perPage = 12;
        $paginatedCards = new \Illuminate\Pagination\LengthAwarePaginator(
            $cards->forPage($page, $perPage),
            $cards->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        //Retorna a la vista indicada la paginacion de las cartas y los filtros seleccionados
        return view('pokemon.index', [
            'cards' => $paginatedCards,
            'searchTerm' => $query,
            'currentRarity' => $rarity
        ]);
    }

    //Funcion para mostrar las cartas
    public function show($id)
    {   
        //Se busca la id de la carta seleccionada
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

        //Retorna la vista a la carta seleccionada y su tipo
        return view('pokemon.show', compact('card', 'type'));
    }
}
