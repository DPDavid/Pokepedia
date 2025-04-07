@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $card->image_large }}"
                class="img-fluid"
                alt="{{ $card->name }}"
                style="max-height: 600px;">
        </div>
        <div class="col-md-6">
            <!--Nombre de la carta-->
            <h1>{{ $card->name }}</h1>

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
            @auth
            <form action="{{ route('favorites.toggle', [$type, $card->{$type.'_id'}]) }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="btn btn-danger">
                    @php
                    $exists = Auth::user()->favorites()->where($type.'_id', $card->{$type.'_id'})->exists();
                    @endphp
                    <i class="bi bi-heart{{ $exists ? '-fill' : '' }}"></i>
                    {{ $exists ? 'Quitar de favoritos' : 'Añadir a favoritos' }}
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-danger mb-3">
                <i class="bi bi-heart"></i> Inicia sesión para guardar favoritos
            </a>
            @endauth

            <div class="card mb-3">
                <div class="card-body">
                    @if($type === 'pokemon')
                    <p><strong>HP:</strong> {{ $card->hp }}</p>
                    <p><strong>Nivel:</strong> {{ $card->level ?? 'N/A' }}</p>
                    <p><strong>Evoluciona de:</strong> {{ $card->evolves_from ?? 'N/A' }}</p>
                    <p><strong>Número Pokédex:</strong> {{ $card->national_pokedex_number ?? 'N/A' }}</p>
                    @endif
                    <p><strong>Rareza:</strong> {{ $card->rarity ?? 'N/A' }}</p>
                </div>
            </div>

            @if($card->flavor_text)
            <div class="card">
                <div class="card-body">
                    <p class="card-text">{{ $card->flavor_text }}</p>
                </div>
            </div>
            @endif

            <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">
                Volver al listado
            </a>
        </div>
    </div>
</div>
@endsection