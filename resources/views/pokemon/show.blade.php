@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Titulo de la página -->
        <div class="text-center mt-4">
            <h1 class="fw-bold">
                <!-- El titulo sirve de enlace para volver al inicio de la página -->
                <a href="{{ route('pokemon.index', ['page' => 1]) }}" class="pokemon-title">
                    PokePedia
                </a>
            </h1>
        </div>

        <!-- Contenedor para el nombre del Pokémon, la etiqueta y el botón de favoritos alineados a la izquierda -->
        <div class="col-md-12 mt-3">
            <div class="d-flex flex-column align-items-start">
                <!-- Nombre de la carta -->
                <h1 class="mb-3">{{ $card->name }}</h1>

                <!-- Etiqueta para las cartas de los pokemons -->
                <span class="badge bg-secondary mb-3">
                    @if($type === 'pokemon')
                    Pokémon Card
                    @elseif($type === 'trainer')
                    Trainer Card
                    @else
                    Energy Card
                    @endif
                </span>

                <!-- Botón de favoritos -->
                @auth
                <!-- Uso del id según el tipo de carta -->
                <form action="{{ route('favorites.toggle', [$type, $card->{$type.'_id'}]) }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="btn btn-danger">
                        @php
                        // Verificación de si el usuario tiene esa carta en favoritos
                        $exists = Auth::user()->favorites()->where($type.'_id', $card->{$type.'_id'})->exists();
                        @endphp
                        <!-- Logo del corazón, si ya está añadida el corazón está relleno -->
                        <i class="bi bi-heart{{ $exists ? '-fill' : '' }}"></i>
                        <!-- Cambio del texto dependiendo de si el usuario tiene o no la carta en favoritos -->
                        {{ $exists ? 'Quitar de favoritos' : 'Añadir a favoritos' }}
                    </button>
                </form>
                @else
                <!-- Cuando el usuario no ha iniciado sesión, cambia la frase y el botón redirige a la ventana para hacerlo -->
                <a href="{{ route('login') }}" class="btn btn-danger mb-3">
                    <i class="bi bi-heart"></i> Inicia sesión para guardar favoritos
                </a>
                @endauth
            </div>
        </div>

        <!-- Información de la carta y la imagen de la carta -->
        <div class="col-md-12 mt-4">
            <div class="row">
                <!-- Imagen de la carta a la izquierda -->
                <div class="col-md-6">
                    <img src="{{ $card->image_large }}" class="img-fluid" alt="{{ $card->name }}" style="max-height: 600px;">
                </div>

                <!-- Información detallada de la carta a la derecha -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Condición para cuando es un pokemon -->
                            @if($type === 'pokemon')
                            <p><strong>HP:</strong> {{ $card->hp }}</p>
                            <p><strong>Nivel:</strong> {{ $card->level ?? 'N/A' }}</p>
                            <p><strong>Evoluciona de:</strong> {{ $card->evolves_from ?? 'N/A' }}</p>
                            <p><strong>Número Pokédex:</strong> {{ $card->national_pokedex_number ?? 'N/A' }}</p>
                            <p><strong>Rareza:</strong> {{ $card->rarity ?? 'N/A' }}</p>

                            <!--Condicion para cuando tiene una debilidad-->
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

                            <!--Condicion para cuando tenga resistencias a un tipo-->
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

                            <!--Condicion para cuando tienen coste de retirada-->
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

                            <!--Condicion para las cartas que son trainers(consumibles)-->
                            @elseif($type === 'trainer')
                            <p><strong>Numero de carta:</strong> {{ $card->number ?? 'N/A' }}</p>
                            <p><strong>Arte:</strong> {{ $card->artist ?? 'N/A' }}</p>
                            <p><strong>Rareza:</strong> {{ $card->rarity ?? 'N/A' }}</p>
                            <!--Si la carta no es un pokemon o un trainer, entonces es una energia-->
                            @else
                            <p><strong>Numero de carta:</strong> {{ $card->number ?? 'N/A' }}</p>
                            <p><strong>Arte:</strong> {{ $card->artist ?? 'N/A' }}</p>
                            <p><strong>Tipo de energia:</strong> {{ $card->subtypes ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Condición para si la carta tiene una descripción -->
                    @if($card->flavor_text)
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">{{ $card->flavor_text }}</p>
                        </div>
                    </div>
                    @endif

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
                </div>
            </div>
        </div>

        <!-- Botón para volver a la página anterior -->
        <a href="{{ route('pokemon.index') }}" class="btn btn-primary mt-3">
            Volver al listado
        </a>
    </div>
</div>
@endsection