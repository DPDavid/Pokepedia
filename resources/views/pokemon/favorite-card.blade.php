<div class="card h-100">
    <div class="position-absolute top-0 end-0 p-2">
        <form action="{{ route('favorites.toggle', [$type, $card->{$type.'_id'}]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger p-1">
                <i class="bi bi-trash-fill"></i>
            </button>
        </form>
    </div>
    
    <a href="{{ $route }}">
        <img src="{{ $card->image_large ?? $card->image_small }}"
            class="card-img-top"
            alt="{{ $card->name }}">
    </a>
    
    <div class="card-body pt-3">
        <h5 class="card-title">{{ $card->name }}</h5>
        @if($type !== 'energy')
        <p class="card-text">
            <small class="text-muted">{{ $card->rarity ?? 'N/A' }}</small>
        </p>
        @endif

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