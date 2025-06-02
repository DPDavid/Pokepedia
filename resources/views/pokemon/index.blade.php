@extends('layouts.app')

@section('content')
<div class="container">

    <!--Iniciar Sesion-->
    <div class="text-end mb-4">
        <!-- Boton para acceder al generador de carats -->
        <a href="{{ route('card.generate') }}" class="btn btn-warning me-2 mb-2">
            <i class="bi bi-magic"></i> Generador Cartas
        </a>

        @auth
        <div class="d-flex align-items-center justify-content-end">
            <!--Nombre del usuario si ha iniciado sesion y boton para su pagina personal-->
            <a href="{{ route('privada') }}" class="btn btn-success me-2">
                <i class="bi bi-person-circle me-1"></i>
                {{ Auth::user()->name }}
            </a>

            <!--Boton para cerrar sesion-->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </button>
            </form>
        </div>
        <!--Si el usuario no esta autentificado, boton para logear -->
        @else
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
        </a>
        @endauth
    </div>

    <!--Titulo de la pahina-->
    <div class="text-center mt-4">
        <h1 class="fw-bold">
            <a href="{{ route('pokemon.index', ['page' => 1]) }}" class="pokemon-title">
                PokePedia
            </a>
        </h1>
    </div>

    <!--Barra de busqueda-->
    <form action="{{ route('pokemon.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <!--Icono de pokeball-->
            <span class="input-group-text bg-white border-end-0">
                <img src="{{ asset('images/Poké_Ball_icon.svg.png') }}" width="25" height="25" alt="" id="pokeball-icon">
            </span>
            <!--Campo del texto para buscar-->
            <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar Pokémon..." value="{{ $searchTerm ?? '' }}" id="search-input">
            <input type="hidden" name="page" value="1">
            <!--Boton de busqueda-->
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <!--Script para ocultar la pokeball al escribir en la barra-->
    <script>
        document.getElementById('search-input').addEventListener('input', function() {
            let icon = document.getElementById('pokeball-icon');
            icon.style.display = this.value ? 'none' : 'block';
        });
    </script>

    <!--Filtros de busqueda-->
    <div class="mb-4">
        <form action="{{ route('pokemon.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
            <input type="hidden" name="search" value="{{ request('search') }}">

            <!--Filtro por rareza-->
            <select name="rarity" class="form-select w-auto">
                <option value="">Todas las rarezas</option>
                @foreach([
                'Common', 'Uncommon', 'Rare', 'Rare Holo', 'Promo',
                'Rare Holo EX', 'Rare Ultra', 'Rare Holo GX', 'Rare Rainbow',
                'Rare Shiny GX', 'Rare Secret', 'Rare Holo V', 'Rare Holo VMAX',
                'Classic Collection', 'Rare Holo Star', 'Double Rare',
                'Illustration Rare', 'Shiny Rare', 'Special Illustration Rare',
                'Hyper Rare', 'Trainer Gallery Rare Holo'
                ] as $rarity)
                <option value="{{ $rarity }}" {{ request('rarity') === $rarity ? 'selected' : '' }}>
                    {{ $rarity }}
                </option>
                @endforeach
            </select>

            <!--Filtro por tipo (energia)-->
            <select name="type" class="form-select w-auto">
                <option value="">Todos los tipos</option>
                @foreach(['fire', 'water', 'grass', 'lightning', 'psychic', 'fighting', 'darkness', 'metal', 'fairy', 'dragon', 'colorless'] as $type)
                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
                @endforeach
            </select>

            <!--Orden de la busqueda-->
            <label for="order_by" class="mb-0">Ordenar por:</label>
            <select name="order_by" id="order_by" class="form-select w-auto">
                <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>Nombre</option>
                <option value="hp" {{ request('order_by') == 'hp' ? 'selected' : '' }}>HP</option>
                <option value="national_pokedex_number" {{ request('order_by') == 'national_pokedex_number' ? 'selected' : '' }}>N° Pokédex</option>
            </select>

            <!--Direccion del orden asc o desc-->
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

            <!--Boton para aplicar filtros-->
            <button type="submit" class="btn btn-outline-primary">Aplicar</button>
        </form>

        <!--Boton para cambiar dirección del orden-->
        <form action="{{ route('pokemon.index') }}" method="GET" class="mt-2">
            <!--Mantiene todos los parametros actuales-->
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="rarity" value="{{ request('rarity') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <input type="hidden" name="order_by" value="{{ request('order_by', 'name') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') === 'asc' ? 'desc' : 'asc' }}">

            <!--Boton para alternar la direccion-->
            <button type="submit" class="btn btn-secondary">
                @if(request('direction') === 'desc')
                <i class="bi bi-sort-up"></i>
                @else
                <i class="bi bi-sort-down"></i>
                @endif
            </button>
        </form>
    </div>

    <!--Listado de cartas-->
    <div class="row">
        @foreach($cards as $card)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <!--Enlace para acceder a la vista de la carta-->
                <a href="{{ route('pokemon.show', ['type' => $card->card_type, 'id' => $card->{$card->card_type.'_id'}]) }}">
                    <inlg src="{{ $card->image_large ?? $card->image_small }}" class="card-img-top" alt="{{ $card->name }}" style="max-height: 300px; object-fit: contain; padding-top: 15px;">
                </a>
                <div class="card-body pt-3">
                    <!--Nombre de la carta-->
                    <h5 class="card-title">{{ $card->name }}</h5>
                    <!--Muestra la rareza de la carta si no es una energia-->
                    @if($card->card_type !== 'energy')
                    <p class="card-text">
                        <small class="text-muted">{{ $card->rarity ?? 'N/A' }}</small>
                    </p>
                    @endif
                    <!--Etiqueta del tipo de carta-->
                    <span class="badge bg-secondary">
                        @if($card->card_type === 'pokemon')
                        Pokémon
                        @elseif($card->card_type === 'trainer')
                        Trainer
                        @else
                        Energy
                        @endif
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center align-items-center mt-4">
        <nav>
            <ul class="pagination">
                <!--Ir a la primera pagina-->
                <li class="page-item {{ $cards->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $cards->appends(request()->query())->url(1) }}" aria-label="Primera">
                        <span aria-hidden="true">&laquo;&laquo; Primera</span>
                    </a>
                </li>

                <!--Pagina anterior-->
                @if ($cards->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo; Anterior</span></li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $cards->appends(request()->query())->previousPageUrl() }}">&laquo; Anterior</a>
                </li>
                @endif

                <!--Pagina siguiente-->
                @if ($cards->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $cards->appends(request()->query())->nextPageUrl() }}">Siguiente &raquo;</a>
                </li>
                @else
                <li class="page-item disabled"><span class="page-link">Siguiente &raquo;</span></li>
                @endif

                <!--Ir a la ultima pagina-->
                <li class="page-item {{ !$cards->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $cards->appends(request()->query())->url($cards->lastPage()) }}" aria-label="Última">
                        <span aria-hidden="true">Última &raquo;&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection