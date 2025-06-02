<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\Trainer;
use App\Models\Energy;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    //Funcion para mostrar las cartas en la pagina con filtros y orden
    public function index(Request $request)
    {
        //Obtiene los vallores de los filtros
        $query = $request->input('search');
        $rarity = $request->input('rarity');
        $typeFilter = $request->input('type');
        $orderBy = $request->input('order_by', 'name');
        $direction = $request->input('direction', 'asc');

        //Coleccion vacia para unir las cartas
        $cards = collect();

        // ------------ Pokémons ------------
        $pokemons = Pokemon::query();

        //Aplicamos filtros si hay busqueda
        if ($query) {
            $pokemons->where('name', 'LIKE', "%{$query}%");
        }
        //Filtro de rareza
        if ($rarity) {
            $pokemons->where('rarity', $rarity);
        }
        //Filtro de energia o tipo del pokemon
        if ($typeFilter) {
            $pokemons->where('type', $typeFilter);
        }
        //Orden por numero de pokedex
        if ($orderBy === 'national_pokedex_number') {
            $pokemons->whereNotNull('national_pokedex_number');
        }
        //Orden segun lo que se seleccione
        if (in_array($orderBy, ['name', 'hp', 'national_pokedex_number'])) {
            $pokemons->orderBy($orderBy, $direction);
        }
        //Ejecutamos la consulta y le agregamos el tipo de carta que es (pokemon)
        $pokemons = $pokemons->get();
        $pokemons->each(function ($item) use (&$cards) {
            $item->card_type = 'pokemon';
            //Añadimos los pokemons a la coleccion
            $cards->push($item);
        });


        //Solo se incluyen si no hay filtro de tipo
        if (!$typeFilter) {
            // ------------ Trainers ------------
            $trainers = Trainer::query();

            //Aplicamos filtros si hay busqueda
            if ($query) {
                $trainers->where('name', 'LIKE', "%{$query}%");
            }
            //Filtro de rareza
            if ($rarity) {
                $trainers->where('rarity', $rarity);
            }
            //Filtro por nombre
            if ($orderBy === 'name') {
                $trainers->orderBy('name', $direction);
            }
            //Ejecutamos la consulta y le agregamos el tipo de carta que es (trainer)
            $trainers = $trainers->get();
            $trainers->each(function ($item) use (&$cards) {
                $item->card_type = 'trainer';
                //Añadimos la carta a la coleccion
                $cards->push($item);
            });

            // ------------ Energías ------------
            $energies = Energy::query();
            //Aplicamos filtros si hay busqueda
            if ($query) {
                $energies->where('name', 'LIKE', "%{$query}%");
            }
            //Filtro por nombre
            if ($orderBy === 'name') {
                $energies->orderBy('name', $direction);
            }
            //Ejecutamos la consulta y le agregamos el tipo de carta que es (energia)
            $energies = $energies->get();
            $energies->each(function ($item) use (&$cards) {
                $item->card_type = 'energy';
                //Añadimos la carta a la coleccion
                $cards->push($item);
            });
        }

        // ------------ PAGINACIÓN MANUAL ------------

        //Pagina actual
        $page = $request->input('page', 1);
        //Numero de cartas por pagina
        $perPage = 12;

        //Usamos una paginacion manual por la combinacion de colecciones de diferente modelo
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

        //Retornamos a la vista con los datos necesarios
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
        //Buscamos la carta por su ID, empezando con los pokemons
        $card = Pokemon::where('pokemon_id', $id)->first();
        $type = 'pokemon';

        //Si no se encunetran los pokemons, buscamos entrenadores
        if (!$card) {
            $card = Trainer::where('trainer_id', $id)->first();
            $type = 'trainer';
        }

        //Igual con las energias
        if (!$card) {
            $card = Energy::where('energy_id', $id)->firstOrFail();
            $type = 'energy';
        }

        //Retornamos a la vista show con la carta correspondiente
        return view('pokemon.show', compact('card', 'type'));
    }
}
