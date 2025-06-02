@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <!--Titulo de la pagina-->
        <div class="text-center mt-4">
            <h1 class="fw-bold">
                <!--Enlace para volver a la pagina principal-->
                <a href="{{ route('pokemon.index', ['page' => 1]) }}" class="pokemon-title">
                    PokePedia
                </a>
            </h1>
        </div>

        <!--Contenedor para el nombre de la carta, la etiqueta y el boton de favoritos-->
        <div class="col-md-12 mt-3">
            <div class="d-flex flex-column align-items-start">
                <!-- Nombre de la carta -->
                <h1 class="mb-3">{{ $card->name }}</h1>
                <!-- Etiqueta para identificar la carta que es -->
                <span class="badge bg-secondary mb-3">
                    @if($type === 'pokemon')
                    Pokémon Card
                    @elseif($type === 'trainer')
                    Trainer Card
                    @else
                    Energy Card
                    @endif
                </span>

                <!-- Boton de favoritos (visible si ha iniciado sesion)-->
                @auth
                <form action="{{ route('favorites.toggle', [$type, $card->{$type.'_id'}]) }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                    <button type="submit" class="btn btn-danger">
                        @php
                        // Verificación de si el usuario tiene esa carta en favoritos
                        $exists = Auth::user()->favorites()->where($type.'_id', $card->{$type.'_id'})->exists();
                        @endphp
                        <!--Logo del corazon, si ya esta añadida el corazon está relleno-->
                        <i class="bi bi-heart{{ $exists ? '-fill' : '' }}"></i>
                        <!--Cambio del texto dependiendo de si el usuario tiene o no la carta en favoritos-->
                        {{ $exists ? 'Quitar de favoritos' : 'Añadir a favoritos' }}
                    </button>
                </form>
                @else
                <!--Boton para iniciar sesion si no esta logeado-->
                <a href="{{ route('login') }}" class="btn btn-danger mb-3">
                    <i class="bi bi-heart"></i> Inicia sesión para guardar favoritos
                </a>
                @endauth
            </div>
        </div>

        <!--Contenedor e información de la carta-->
        <div class="col-md-12 mt-4">
            <div class="row">

                <!-- Imagen de la carta a la izquierda -->
                <div class="col-md-6">
                    <a href="{{ $card->image_large }}">
                        <img
                            src="{{ $card->image_large }}"
                            class="img-fluid"
                            alt="{{ $card->name }}"
                            style="max-height: 600px;"
                            title="Precio: Mín: ${{ $card->price_low ?? 'N/A' }} - Máx: ${{ $card->price_high ?? 'N/A' }}">
                    </a>
                </div>

                <!--Información detallada de la carta-->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <!--Informacion especifica si es una carta pokemon-->
                            @if($type === 'pokemon')
                            <p><strong>HP:</strong> {{ $card->hp }}</p>
                            <p><strong>Evoluciona de:</strong> {{ $card->evolves_from ?? 'N/A' }}</p>
                            <p><strong>Número Pokédex:</strong> {{ $card->national_pokedex_number ?? 'N/A' }}</p>
                            <p><strong>Rareza:</strong> {{ $card->rarity ?? 'N/A' }}</p>

                            <!--Debilidad-->
                            @if (!empty($card->weaknesses))
                            <p><strong>Debilidad:</strong></p>
                            @foreach ($card->weaknesses as $weakness)
                            <img src="{{ asset('images/energy/' . $weakness['type'] . '.png') }}"
                                alt="{{ $weakness['type'] }}" title="{{ $weakness['type'] }}"
                                style="height: 24px; width: 24px; margin-right: 4px;">
                            <span class="badge bg-danger">{{ $weakness['value'] }}</span>
                            @endforeach
                            @else
                            <p><strong>Debilidad:</strong> N/A</p>
                            @endif

                            <!--Resistencias-->
                            @if (!empty($card->resistances))
                            <p><strong>Resistencias:</strong></p>
                            @foreach ($card->resistances as $resistance)
                            <img src="{{ asset('images/energy/' . $resistance['type'] . '.png') }}"
                                alt="{{ $resistance['type'] }}" title="{{ $resistance['type'] }}"
                                style="height: 24px; width: 24px; margin-right: 4px;">
                            <span class="badge bg-danger">{{ $resistance['value'] }}</span>
                            @endforeach
                            @else
                            <p><strong>Resistencias:</strong> N/A </p>
                            @endif

                            <!--Coste de retirada-->
                            @if ($card->retreatCosts && count($card->retreatCosts) > 0)
                            <p><strong>Coste de Retirada:</strong></p>
                            @foreach ($card->retreatCosts as $retreat)
                            <img src="{{ asset('images/energy/' . $retreat->type . '.png') }}"
                                alt="{{ $retreat->type }}" title="{{ $retreat->type }}"
                                style="height: 24px; width: 24px; margin-right: 4px;">
                            @endforeach
                            @else
                            <p><strong>Coste de Retirada:</strong> N/A</p>
                            @endif

                            <!--Informacion especifica si es una carta de entrenador-->
                            @elseif($type === 'trainer')
                            <p><strong>Numero de carta:</strong> {{ $card->number ?? 'N/A' }}</p>
                            <p><strong>Arte:</strong> {{ $card->artist ?? 'N/A' }}</p>
                            <p><strong>Rareza:</strong> {{ $card->rarity ?? 'N/A' }}</p>
                            @else

                            <!--Informacion especifica si es una carta de energia-->
                            <p><strong>Numero de carta:</strong> {{ $card->number ?? 'N/A' }}</p>
                            <p><strong>Arte:</strong> {{ $card->artist ?? 'N/A' }}</p>
                            <p><strong>Tipo de energia:</strong>
                                {{ is_array($card->subtypes) ? implode(', ', $card->subtypes) : ($card->subtypes ?? 'N/A') }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <!--Descripcion de la carta-->
                    @if($card->flavor_text)
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">{{ $card->flavor_text }}</p>
                        </div>
                    </div>
                    @endif

                    <!--Contenedor de ataques, solo si la carta es de tipo pokemon-->
                    @if ($card->attacks && count($card->attacks) > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <strong>Ataques</strong>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($card->attacks as $attack)
                            <li class="list-group-item">
                                <p><strong>Nombre:</strong> {{ $attack->name }}</p>
                                <p><strong>Daño:</strong> {{ $attack->damage ?? 'N/A' }}</p>
                                <p><strong>Descripción:</strong> {{ $attack->text ?? 'N/A' }}</p>
                                <p><strong>Coste Energético:</strong>
                                    @if ($attack->cost)
                                    @foreach (json_decode($attack->cost) as $energy)
                                    <img src="{{ asset('images/energy/' . $energy . '.png') }}"
                                        alt="{{ $energy }}" title="{{ $energy }}"
                                        style="height: 24px; width: 24px; margin-right: 4px;">
                                    @endforeach
                                    @else
                                    N/A
                                    @endif
                                </p>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!--Enlace externo al mercado de cartas TCG-->
                    @if ($card->tcgplayer_url)
                    <div class="card mt-3">
                        <div class="card-body">
                            <a href="{{ $card->tcgplayer_url }}" target="_blank" rel="noopener noreferrer" class="text-decoration-underline text-primary fs-4">
                                <i class="bi bi-link"></i> Ver detalles de precios
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!--Boton para volver a la pagina anterior-->
        <button onclick="history.back()" class="btn btn-primary mt-3">
            <i class="bi bi-arrow-left-circle"></i> Volver atrás
        </button>
    </div>
</div>
@endsection