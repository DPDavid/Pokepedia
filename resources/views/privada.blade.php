<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /*Estilos imagen de las cartas */
        .card-img-top {
            max-height: 300px;
            object-fit: contain;
            padding-top: 15px;
        }

        /*Sombra para el contenedor de la carta y transicion de zoom*/
        .card {
            transition: transform 0.3s;
            background: #fff3cd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            position: relative;
        }

        /*Zoon de la carta y sombras cuando zoomea*/
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.72);
        }

        /*Estilos en general de la página*/
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, rgb(225, 27, 27), #f09819, rgb(16, 213, 157), rgb(20, 115, 217));
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            padding: 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
        }

        /*Estilos de la insignia del tipo de carta*/
        .badge-card-type {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="d-flex align-items-center col-md-6 mb-2 mb-md-0">
                <h4 class="mb-0">
                    <i class="bi bi-heart-fill text-danger"></i> Favoritos de {{ Auth::user()->name }}
                </h4>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('pokemon.index') }}" class="btn btn-primary me-2">
                    <i class="bi bi-house-door"></i> Volver al inicio
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </button>
                </form>
            </div>
        </header>

        <article class="mb-5">
            <!--Pestañas para los tipos de carta -->
            <ul class="nav nav-tabs mb-4" id="favoritesTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pokemon-tab" data-bs-toggle="tab" data-bs-target="#pokemon" type="button">
                        Pokémon ({{ count($pokemons) }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="trainer-tab" data-bs-toggle="tab" data-bs-target="#trainer" type="button">
                        Consumibles ({{ count($trainers) }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="energy-tab" data-bs-toggle="tab" data-bs-target="#energy" type="button">
                        Energías ({{ count($energies) }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="favoritesTabContent">
                <!--Pestaña de pokemons -->
                <div class="tab-pane fade show active" id="pokemon" role="tabpanel">
                    @if($pokemons->count() > 0)
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
                    @else
                    <div class="alert alert-info text-center">
                        No tienes Pokémon favoritos aún
                    </div>
                    @endif
                </div>

                <!--Pestaña de entrenadores -->
                <div class="tab-pane fade" id="trainer" role="tabpanel">
                    @if($trainers->count() > 0)
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
                    @else
                    <div class="alert alert-info text-center">
                        No tienes Entrenadores favoritos aún
                    </div>
                    @endif
                </div>

                <!--Pestaña de energias -->
                <div class="tab-pane fade" id="energy" role="tabpanel">
                    @if($energies->count() > 0)
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
                    @else
                    <div class="alert alert-info text-center">
                        No tienes Energías favoritas aún
                    </div>
                    @endif
                </div>
            </div>
        </article>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>