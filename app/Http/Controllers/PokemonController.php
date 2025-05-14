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
        //Obtienelos parametros de busqueda de la carta y la rareza desde la solicitud
        $query = $request->input('search');
        $rarity = $request->input('rarity');

        //Creacion de una coleccion de cartas para que esten todas agrupadas
        $cards = collect();

        //------------Filtro de los pokemons------------
        $pokemons = Pokemon::query();
        //Si hay busqueda, se filtra por nombre
        if ($query) {
            $pokemons->where('name', 'LIKE', "%{$query}%");
        }
        //Si se selecciona una rareza, se filtra por ella
        if ($rarity) {
            $pokemons->where('rarity', $rarity);
        }
        //Ordena el resultado alfabeticamente por su nombre
        $pokemons = $pokemons->orderBy('name', 'asc')->get();
        //Se le agrega el tipo de carta (pokemon) y se añade a la coleccion
        $pokemons->each(function ($item) use (&$cards) {
            $item->card_type = 'pokemon';
            $cards->push($item);
        });


        //------------Filtro de los entrenadores (consumibles)------------
        $trainers = Trainer::query();
        //Si hay busqueda, se filtra por nombre
        if ($query) {
            $trainers->where('name', 'LIKE', "%{$query}%");
        }
        //Si se selecciona una rareza, se filtra por ella
        if ($rarity) {
            $trainers->where('rarity', $rarity);
        }
        //Ordena el resultado alfabeticamente por su nombre
        $trainers = $trainers->orderBy('name', 'asc')->get();
        //Se le agrega el tipo de carta (trainer) y se añade a la coleccion
        $trainers->each(function ($item) use (&$cards) {
            $item->card_type = 'trainer';
            $cards->push($item);
        });


        //------------Filtro de las energias------------
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

        //------------Paginacion de la cartas------------
        //Pagina por defecto
        $page = $request->input('page', 1);
        //Numero de cartas por pagina
        $perPage = 12;

        //Paginacion manual de la coleccion
        $paginatedCards = new \Illuminate\Pagination\LengthAwarePaginator(
            //Elementos de la pagina actual
            $cards->forPage($page, $perPage),
            //Total de elementos
            $cards->count(),
            //Elementos por pagina
            $perPage,
            //Pagina actual
            $page,
            //Mantiene los filtros en la URL
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        //Se retorna la vista 'pokemon.index' con las cartas paginadas y filtros actuales
        return view('pokemon.index', [
            'cards' => $paginatedCards,
            'searchTerm' => $query,
            'currentRarity' => $rarity
        ]);
    }

    //Funcion para mostrar los detalles de una carta
    public function show($id)
    {
        //Se busca la carta primero como un pokemon
        $card = Pokemon::where('pokemon_id', $id)->first();
        $type = 'pokemon';

        //Si no es un pokemon se busca como un entrenador (consumible) 
        if (!$card) {
            $card = Trainer::where('trainer_id', $id)->first();
            $type = 'trainer';
        }

        //Si tampoco es un entrenador se intenta con una energia 
        if (!$card) {
            $card = Energy::where('energy_id', $id)->firstOrFail();
            $type = 'energy';
        }

        //Retorna la vista 'pokemon.show' con la carta seleccionada y su tipo
        return view('pokemon.show', compact('card', 'type'));
    }
}
