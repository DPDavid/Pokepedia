<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-img-top {
            max-height: 300px;
            object-fit: contain;
            padding-top: 15px;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.03);
        }
    </style>
</head>

<body style="background-color:whitesmoke;">
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
            @if($favorites->count() > 0)
                <div class="row">
                    @foreach($favorites as $pokemon)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="position-absolute top-0 end-0 p-2">
                                <form action="{{ route('favorites.toggle', $pokemon->pokemon_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger p-1">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>
                            </div>
                            <a href="{{ route('pokemon.show', $pokemon->pokemon_id) }}">
                                <img src="{{ $pokemon->image_large ?? $pokemon->image_small }}"
                                    class="card-img-top"
                                    alt="{{ $pokemon->name }}">
                            </a>
                            <div class="card-body pt-3">
                                <h5 class="card-title">{{ $pokemon->name }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">{{ $pokemon->rarity }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center">
                    {{ $favorites->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="alert alert-info">
                        <h4><i class="bi bi-heart"></i> Aún no tienes pokémons favoritos</h4>
                        <p class="mt-3">
                            <a href="{{ route('pokemon.index') }}" class="btn btn-primary">
                                <i class="bi bi-search"></i> Explorar pokémons
                            </a>
                        </p>
                    </div>
                </div>
            @endif
        </article>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>