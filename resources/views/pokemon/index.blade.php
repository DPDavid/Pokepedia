@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Cartas Pokémon</h1>
    
    <!-- Barra de búsqueda -->
    <form action="{{ route('pokemon.search') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar Pokémon..." value="{{ $searchTerm ?? '' }}">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <!-- Filtros por rareza -->
    <div class="mb-4">
        <h5>Filtrar por rareza:</h5>
        <div class="btn-group">
            <a href="{{ route('pokemon.index') }}" class="btn btn-outline-secondary">Todos</a>
            @foreach(['Common', 'Uncommon', 'Rare', 'Rare Holo'] as $rarity)
                <a href="{{ route('pokemon.rarity', $rarity) }}" class="btn btn-outline-primary">{{ $rarity }}</a>
            @endforeach
        </div>
    </div>

    <!-- Listado de cartas -->
    <div class="row">
        @foreach($pokemons as $pokemon)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <a href="{{ route('pokemon.show', $pokemon->pokemon_id) }}">
                        <img src="{{ $pokemon->image_large ?? $pokemon->image_small }}" 
                             class="card-img-top" 
                             alt="{{ $pokemon->name }}"
                             style="max-height: 300px; object-fit: contain;">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $pokemon->name }}</h5>
                        <p class="card-text">
                            <small class="text-muted">{{ $pokemon->rarity }}</small>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $pokemons->links() }}
    </div>
</div>
@endsection