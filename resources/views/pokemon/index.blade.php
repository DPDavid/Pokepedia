@extends('layouts.app')

@section('content')
<div class="container">
    <!--Iniciar Sesion-->
    <div class="text-end mb-4">
        <!--Solo se muestra si el usuario ha iniciado sesion-->
        @auth
        <div class="d-flex align-items-center justify-content-end">
            <!--Muestra el nombre de usuario y un icono especial-->
            <a href="{{ route('privada') }}" class="btn btn-success me-2">
                <i class="bi bi-person-circle me-1"></i>
                {{ Auth::user()->name }}
            </a>
            <!--Accion para cerrar sesion-->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <!--Boton en rojo con un icono para salir-->
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </button>
            </form>
        </div>
        @else
        <!--Si la sesion no se ha iniciado, muestra  un boton para inicar sesion-->
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
        </a>
        @endauth
    </div>
    <!--Titulo de la página-->
    <div class="text-center mt-4">
        <h1 class="fw-bold">
            <!--El titulo sirve de enlace para volver al inicio de la pagia-->
            <a href="{{ route('pokemon.index', ['page' => 1]) }}"
                class="pokemon-title">
                PokePedia
            </a>
        </h1>
    </div>

    <!-- Barra de búsqueda -->
    <form action="{{ route('pokemon.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <!--Imagen para la barra de búsqueda-->
                <img src="{{ asset('images/Poké_Ball_icon.svg.png') }}" width="25" height="25" alt="" id="pokeball-icon">
            </span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar Pokémon..." value="{{ $searchTerm ?? '' }}" id="search-input">
            <input type="hidden" name="page" value="1">
            <!--Boton para buscar al pokemon-->
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <!--Script para ocultar el icono cuado se escriba en la pagina-->
    <script>
        document.getElementById('search-input').addEventListener('input', function() {
            let icon = document.getElementById('pokeball-icon');
            icon.style.display = this.value ? 'none' : 'block';
        });
    </script>

    <!-- Filtros por rareza-->
    <div class="mb-4">
        <!--Titulo-->
        <h5>Filtrar por rareza:</h5>
        <div class="dropdown">
            <!--Desplegable con mensaje default, si se selecciona uno se muestra ese-->
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
                <!--Listado de todas las rarezas de cartas-->
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
        @foreach($cards as $card)
        <div class="col-md-3 mb-4">
            <!--Imagen de la tarjeta-->
            <div class="card h-100">
                <!--Enlace a la carta especifica-->
                <a href="{{ route('pokemon.show', ['type' => $card->card_type, 'id' => $card->{$card->card_type.'_id'}]) }}">
                    <!--Imagen de la carta-->
                    <img src="{{ $card->image_large ?? $card->image_small }}" class="card-img-top" alt="{{ $card->name }}" style="max-height: 300px; object-fit: contain; padding-top: 15px;">
                </a>
                <!--Cuerpo de la tarjeta-->
                <div class="card-body pt-3">
                    <!--Nombre de la carta-->
                    <h5 class="card-title">{{ $card->name }}</h5>
                    <!--Condicion para si la carta es energia no muestre la rareza-->
                    @if($card->card_type !== 'energy')
                    <p class="card-text">
                        <small class="text-muted">{{ $card->rarity ?? 'N/A' }}</small>
                    </p>
                    @endif
                    <!--Etiqueta para las cartas de los pokemons-->
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

    <!--Paginacion-->
    <div class="d-flex justify-content-center align-items-center mt-4">
        <nav>
            <ul class="pagination">
                <!-- Botón Anterior -->
                @if ($cards->currentPage() == 1)
                <li class="page-item disabled">
                    <span class="page-link">&laquo; Anterior</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $cards->appends(request()->query())->url($cards->currentPage() - 1) }}">&laquo; Anterior</a>
                </li>
                @endif

                <!-- Botón Siguiente -->
                @if ($cards->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $cards->appends(request()->query())->nextPageUrl() }}">Siguiente &raquo;</a>
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