@extends('layouts.app')
<div class="container">
    <!--Encabezado de la pagina-->
    <header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <!--Titulo de la pagina, con el nombre del usuario que ha iniciado sesion-->
        <div class="d-flex align-items-center col-md-6 mb-2 mb-md-0">
            <h4 class="mb-0">
                <i class="bi bi-heart-fill text-danger"></i> Favoritos de {{ Auth::user()->name }}
            </h4>
        </div>
        <div class="col-md-6 text-end">
            <!--Boton para volver a la pagina principal-->
            <a href="{{ route('pokemon.index') }}" class="btn btn-primary me-2">
                <i class="bi bi-house-door"></i> Volver al inicio
            </a>
            <!--Boton para cerrar la sesion-->
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </header>

    <article class="mb-5">
        <!--Pestañas para los tipos de carta-->
        <ul class="nav nav-tabs mb-4" id="favoritesTab" role="tablist">
            <!--Pestaña para los pokemons-->
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pokemon-tab" data-bs-toggle="tab" data-bs-target="#pokemon"
                    type="button">
                    Pokémon ({{ count($pokemons) }})
                </button>
            </li>
            <!--Pestaña para los consumibles-->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="trainer-tab" data-bs-toggle="tab" data-bs-target="#trainer" type="button">
                    Consumibles ({{ count($trainers) }})
                </button>
            </li>
            <!--Pestaña para las energias-->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="energy-tab" data-bs-toggle="tab" data-bs-target="#energy" type="button">
                    Energías ({{ count($energies) }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="favoritesTabContent">
            <!--Pestaña de pokemons -->
            <div class="tab-pane fade show active" id="pokemon" role="tabpanel">
                <!--Condicion para cuando tenga algun pokemon añadido a favoritos-->
                @if($pokemons->count() > 0)
                            <!--Contenido de la pestaña pokemon-->
                            <div class="row">
                                @foreach($pokemons as $pokemon)
                                                <div class="col-md-3 mb-4">
                                                    @include('pokemon.favorite-card', [
                                                        'card' => $pokemon,
                                                        'type' => 'pokemon',
                                                        'route' => route('pokemon.show', ['type' => 'pokemon', 'id' => $pokemon->pokemon_id])
                                                    ])
                                                </div>
                                @endforeach
                            </div>
                            <!--Si no cumple la concicion muestra un mensaje en pantalla-->
                @else
                    <div class="alert alert-info text-center">
                        No tienes pokemons favoritos aún
                    </div>
                @endif
            </div>

            <!--Pestaña de entrenadores -->
            <div class="tab-pane fade" id="trainer" role="tabpanel">
                <!--Condicion para cuando tenga algun consumible-entrenador añadido a favoritos-->
                @if($trainers->count() > 0)
                            <!--Contenido de la pestaña consumible-->
                            <div class="row">
                                @foreach($trainers as $trainer)
                                                <div class="col-md-3 mb-4">
                                                    @include('pokemon.favorite-card', [
                                                        'card' => $trainer,
                                                        'type' => 'trainer',
                                                        'route' => route('pokemon.show', ['type' => 'trainer', 'id' => $trainer->trainer_id])
                                                    ])
                                                </div>
                                @endforeach
                            </div>
                            <!--Si no cumple la concicion muestra un mensaje en pantalla-->
                @else
                    <div class="alert alert-info text-center">
                        No tienes consumibles-entrenadores favoritos aún
                    </div>
                @endif
            </div>

            <!--Pestaña de energias -->
            <div class="tab-pane fade" id="energy" role="tabpanel">
                <!--Condicion para cuando tenga alguna energia añadido a favoritos-->
                @if($energies->count() > 0)
                            <!--Contenido de la pestaña energias-->
                            <div class="row">
                                @foreach($energies as $energy)
                                                <div class="col-md-3 mb-4">
                                                    @include('pokemon.favorite-card', [
                                                        'card' => $energy,
                                                        'type' => 'energy',
                                                        'route' => route('pokemon.show', ['type' => 'energy', 'id' => $energy->energy_id])
                                                    ])
                                                </div>
                                @endforeach
                            </div>
                            <!--Si no cumple la concicion muestra un mensaje en pantalla-->
                @else
                    <div class="alert alert-info text-center">
                        No tienes energías favoritas aún
                    </div>
                @endif
            </div>
        </div>
    </article>
</div>