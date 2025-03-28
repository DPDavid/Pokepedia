@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $pokemon->image_large }}" 
                 class="img-fluid" 
                 alt="{{ $pokemon->name }}"
                 style="max-height: 600px;">
        </div>
        <div class="col-md-6">
            <h1>{{ $pokemon->name }}</h1>
            
            <!--Boton de favoritos-->
            @auth
            <form action="{{ route('favorites.toggle', $pokemon->pokemon_id) }}" method="POST" class="mb-3">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-heart{{ Auth::user()->favoritePokemons->contains($pokemon->pokemon_id) ? '-fill' : '' }}"></i>
                    {{ Auth::user()->favoritePokemons->contains($pokemon->pokemon_id) ? 'Quitar de favoritos' : 'Añadir a favoritos' }}
                </button>
            </form>
            <!--Enlace si no hay ninguna carta añadida a favoritos-->
            @else
            <a href="{{ route('login') }}" class="btn btn-danger mb-3">
                <i class="bi bi-heart"></i> Inicia sesión para guardar favoritos
            </a>
            @endauth
            
            <div class="card mb-3">
                <div class="card-body" class="border border-secondaryordw">
                    <p><strong>HP:</strong> {{ $pokemon->hp }}</p>
                    <p><strong>Nivel:</strong> {{ $pokemon->level ?? 'N/A' }}</p>
                    <p><strong>Evoluciona de:</strong> {{ $pokemon->evolves_from ?? 'N/A' }}</p>
                    <p><strong>Rareza:</strong> {{ $pokemon->rarity ?? 'N/A' }}</p>
                    <p><strong>Número Pokédex:</strong> {{ $pokemon->national_pokedex_number ?? 'N/A' }}</p>
                </div>
            </div>

            @if($pokemon->flavor_text)
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">{{ $pokemon->flavor_text }}</p>
                    </div>
                </div>
            @endif

            <a href="{{ route('pokemon.index') }}" class="btn btn-primary mt-3">Volver al listado</a>
        </div>
    </div>
</div>
@endsection