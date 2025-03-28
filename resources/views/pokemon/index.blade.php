@extends('layouts.app')

@section('content')
<div class="container">
    <!--Iniciar Sesion-->
    <div class="text-end mb-4">
        @auth
            <div class="d-flex align-items-center justify-content-end">
                <a href="{{ route('privada') }}" class="btn btn-success me-2">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ Auth::user()->name }}
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
            </a>
        @endauth
    </div>
    <!--Titulo de la página-->
    <div class="text-center mt-4">
        <h1 class="fw-bold">
            <a href="{{ route('pokemon.index', ['page' => 1]) }}" class="text-dark text-decoration-none">
                Cartas Pokémon
            </a>
        </h1>
    </div>

    <!-- Barra de búsqueda -->
    <form action="{{ route('pokemon.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <img src="{{ asset('images/Poké_Ball_icon.svg.png') }}" width="30" height="30" alt="" style="margin-top: 3px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar Pokémon..." value="{{ $searchTerm ?? '' }}">
            <input type="hidden" name="page" value="1"> <!-- Reinicia a la página 1 -->
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
                <li>
                    <a class="dropdown-item" href="{{ route('pokemon.index', ['rarity' => $rarity, 'search' => request('search')]) }}">
                        {{ $rarity }}
                    </a>
                </li>
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

    <!--Paginacion-->
    <div class="d-flex justify-content-center align-items-center mt-4">
        <nav>
            <ul class="pagination">
                <!-- Botón Anterior -->
                @if ($pokemons->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo; Anterior</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $pokemons->appends(request()->query())->previousPageUrl() }}">&laquo; Anterior</a>
                </li>
                @endif

                <!-- Botón Siguiente -->
                @if ($pokemons->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $pokemons->appends(request()->query())->nextPageUrl() }}">Siguiente &raquo;</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link">Siguiente &raquo;</span>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endsection