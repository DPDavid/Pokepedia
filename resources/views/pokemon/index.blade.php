@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Cartas Pokémon</h1>

    <!-- Barra de búsqueda -->
    <form action="{{ route('pokemon.search') }}" method="GET" class="mb-4">
        <div class="input-group">
            <!-- Logo de pokemon ajustado-->
            <img src="{{ asset('images/Poké_Ball_icon.svg.png') }}" width="30" height="30" alt="" style="margin-top: 3px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar Pokémon..." value="{{ $searchTerm ?? '' }}">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <!-- Filtros por rareza-->
    <div class="mb-4">
        <h5>Filtrar por rareza:</h5>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="rarityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $currentRarity ?? 'Todas las rarezas' }}
            </button>
            <!--Todas las rarezas de cartas de pokemon-->
            <ul class="dropdown-menu" aria-labelledby="rarityDropdown" style="max-height: 400px; overflow-y: auto;">
                <li><a class="dropdown-item" href="{{ route('pokemon.index') }}">Todas</a></li>
                @foreach([
                'Common', 'Uncommon', 'Rare', 'Rare Holo', 'Promo',
                'Rare Holo EX', 'Rare Ultra', 'Rare Holo GX', 'Rare Rainbow',
                'Rare Shiny GX', 'Rare Secret', 'Rare Holo V', 'Rare Holo VMAX',
                'Classic Collection', 'Rare Holo Star', 'Double Rare',
                'Illustration Rare', 'Shiny Rare', 'Special Illustration Rare',
                'Hyper Rare', 'Trainer Gallery Rare Holo'
                ] as $rarity)
                <li><a class="dropdown-item" href="{{ route('pokemon.rarity', $rarity) }}">{{ $rarity }}</a></li>
                @endforeach
            </ul>
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
                        style="max-height: 300px; object-fit: contain; padding-top: 15px;">
                </a>

                <div class="card-body pt-3">
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