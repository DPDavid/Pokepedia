<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    //Funcion para listar las cartas pokemon por varios metodos
    public function index(Request $request)
    {
        $query = Pokemon::query();

        //Busqueda de pokemon por el nombre introducido en el buscador
        if ($searchTerm = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        //Filtro de todas las rarezas que hay de tipos de cartas
        if ($currentRarity = $request->input('rarity')) {
            $query->where('rarity', $currentRarity);
        }

        //Ordena las cartas por su nombre de manera asccendente(se cambiara para poder hacerlo tambien de manera descendente)
        $pokemons = $query->orderBy('name', 'asc')
            //muestra las cartas de 20 en 20
            ->paginate(20)
            ->appends($request->except('page'));
        
        //retorna en la vista los filtros ya sean el nombre en el buscador, el filtro de rarezas y el orden de los pokemons
        return view('pokemon.index', compact('pokemons', 'searchTerm', 'currentRarity'));
    }

    //Funcion para mostrar individualmente los pokemons
    public function show($id)
    {
        //Muestra al pokemon por su id
        $pokemon = Pokemon::where('pokemon_id', $id)->firstOrFail();
        //Retorna en la vista show el pokemon dado su id
        return view('pokemon.show', compact('pokemon'));
    }

    //Funcion para buscar los pokemons con un buscador
    public function search(Request $request)
    {
        //Obtencion del nombre del pokemon por el buscador
        $searchTerm = $request->input('search');

        //Filtra el nombre del pokemon
        $pokemons = Pokemon::where('name', 'LIKE', "%{$searchTerm}%")
            ->paginate(20)
            ->appends(['search' => $searchTerm]);

        //Muestra en la vista el pokemons buscado
        return view('pokemon.index', compact('pokemons', 'searchTerm'));
    }

    //Funcion para filtrar a los pokemons por su rareza
    public function filterByRarity($rarity)
    {
        //Adquiere las cartas pokemon que tienen esa rareza
        $pokemons = Pokemon::where('rarity', $rarity)
            ->paginate(20);

        //Retorna en la vista las cartas con la rareza seleccionada
        return view('pokemon.index', compact('pokemons', 'rarity'));
    }
}
