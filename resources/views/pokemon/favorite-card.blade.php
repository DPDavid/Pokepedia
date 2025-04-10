<!--Contenedor de la tarjeta con 100% de espacio-->
<div class="card h-100">
    <!--Boton de eliminar de favortios arriba a la derecha-->
    <div class="position-absolute top-0 end-0 p-2">
        <!--Accion que quita la carta de favoritos-->
        <form action="{{ route('favorites.toggle', [$type, $card->{$type.'_id'}]) }}" method="POST">
            @csrf
            <!--Boton con icono de cubo de basura-->
            <button type="submit" class="btn btn-sm btn-danger p-1">
                <i class="bi bi-trash-fill"></i>
            </button>
        </form>
    </div>

    <!--Imagen de la carta en favoritos-->
    <a href="{{ $route }}">
        <img src="{{ $card->image_large ?? $card->image_small }}" class="card-img-top" alt="{{ $card->name }}">
    </a>

    <!--Cuerpo de la tarjeta de carta-->
    <div class="card-body pt-3">
        <!--Nombre de carta-->
        <h5 class="card-title">{{ $card->name }}</h5>
        <!--Condicion de si la carta es una energia no mostrar la rareza de la carta-->
        @if($type !== 'energy')
        <p class="card-text">
            <small class="text-muted">{{ $card->rarity ?? 'N/A' }}</small>
        </p>
        @endif

        <!--Etiqueta para las cartas de los pokemons-->
        <span class="badge bg-secondary">
            @if($type === 'pokemon')
            Pokémon
            @elseif($type === 'trainer')
            Trainer
            @else
            Energy
            @endif
        </span>
    </div>

</div>