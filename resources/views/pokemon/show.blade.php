@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
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

        <!--Imagen de la carta a la izqueirda-->
        <div class="col-md-6">
            <img src="{{ $card->image_large }}" class="img-fluid" alt="{{ $card->name }}" style="max-height: 600px;">
        </div>

        <div class="col-md-6">
            <!--Nombre de la carta-->
            <h1>{{ $card->name }}</h1>

            <!--Etiqueta para las cartas de los pokemons-->
            <span class="badge bg-secondary mb-3">
                @if($type === 'pokemon')
                Pokémon Card
                @elseif($type === 'trainer')
                Trainer Card
                @else
                Energy Card
                @endif
            </span>

            <!--Boton de favoritos-->
            <!--Solo muestra si el usuario ha iniciado sesion-->
            @auth
            <!--Uso del id segun el tipo de carta-->
            <form action="{{ route('favorites.toggle', [$type, $card->{$type.'_id'}]) }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="btn btn-danger">
                    @php
                    //Verificacion de si el usuario tiene esa carta en favoritos
                    $exists = Auth::user()->favorites()->where($type.'_id', $card->{$type.'_id'})->exists();
                    @endphp
                    <!--Logo del corazon, si ya esta añadida el corazon esta relleno-->
                    <i class="bi bi-heart{{ $exists ? '-fill' : '' }}"></i>
                    <!--Cambio del texto dependiendo de si el usaurio tiene o no la carta en favoritos-->
                    {{ $exists ? 'Quitar de favoritos' : 'Añadir a favoritos' }}
                </button>
            </form>
            @else
            <!--Cuando el usuario no ha iniciado sesion, cambia la frase y el boton redirige a la ventana para hacerlo-->
            <a href="{{ route('login') }}" class="btn btn-danger mb-3">
                <i class="bi bi-heart"></i> Inicia sesión para guardar favoritos
            </a>
            @endauth

            <!--Informacion de la carta-->
            <div class="card mb-3">
                <div class="card-body">
                    <!--Condicion para cuando es un pokemon-->
                    @if($type === 'pokemon')
                    <p><strong>HP:</strong> {{ $card->hp }}</p>
                    <p><strong>Nivel:</strong> {{ $card->level ?? 'N/A' }}</p>
                    <p><strong>Evoluciona de:</strong> {{ $card->evolves_from ?? 'N/A' }}</p>
                    <p><strong>Número Pokédex:</strong> {{ $card->national_pokedex_number ?? 'N/A' }}</p>
                    <p><strong>Rareza:</strong> {{ $card->rarity ?? 'N/A' }}</p>
                    <!--Condicion para cuando es una carta de consumibles-->
                    @elseif($type === 'trainer')
                    <p><strong>Numero de carta:</strong> {{ $card->number ?? 'N/A' }}</p>
                    <p><strong>Arte:</strong> {{ $card->artist ?? 'N/A' }}</p>
                    <p><strong>Rareza:</strong> {{ $card->rarity ?? 'N/A' }}</p>
                    <!--Condicion para cuando es una energia-->
                    @else
                    <p><strong>Numero de carta:</strong> {{ $card->number ?? 'N/A' }}</p>
                    <p><strong>Arte:</strong> {{ $card->artist ?? 'N/A' }}</p>
                    <p><strong>Tipo de energia:</strong> {{ $card->subtypes ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>

            <!--Condicion para si la carta tiene una descripcion-->
            @if($card->flavor_text)
            <div class="card">
                <!--Muestra la descripcion asociada a la carta-->
                <div class="card-body">
                    <p class="card-text">{{ $card->flavor_text }}</p>
                </div>
            </div>
            @endif

            <!--Boton para volver a la pagina anterior-->
            <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">
                Volver al listado
            </a>
        </div>
    </div>
</div>
@endsection