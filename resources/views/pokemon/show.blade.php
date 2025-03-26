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