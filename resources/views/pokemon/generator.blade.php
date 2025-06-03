@extends('layouts.app')

@section('content')
<div class="container">

    <!--Titulo de la pagina-->
    <div class="text-center mt-4">
        <h1 class="fw-bold">
            <!--Enlace para volver a pagina principal-->
            <a href="{{ route('pokemon.index') }}" class="pokemon-title">
                PokePedia
            </a>
        </h1>
        <p class="text-white-50">Crea tus propias cartas personalizadas de Pokémon</p>
    </div>

    <!--Muestra los errores de validacion del formulario-->
    @if($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row mt-4">
        <!-- Formulario -->
        <div class="col-md-6">
            <form action="{{ route('card.generate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!--Nombre de la carta-->
                    <div class="col-12">
                        <label for="name" class="form-label text-white">Nombre de la Carta</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ej. Pikachu EX" required>
                    </div>

                    <!--Plantilla de la carta-->
                    <div class="col-12">
                        <label for="template" class="form-label text-white">Plantilla</label>
                        <div class="position-relative">
                            <select name="template" id="template" class="form-select bg-white text-dark" required>
                                <option value="pokemoncard1">Eléctrico</option>
                                <option value="pokemoncard2">Planta</option>
                                <option value="pokemoncard3">Fuego</option>
                                <option value="pokemoncard4">Psíquico</option>
                                <option value="pokemoncard5">Agua</option>
                            </select>
                        </div>
                    </div>

                    <!--Selector del color de fondo de la imagen-->
                    <div class="col-12">
                        <label for="bg_color" class="form-label text-white">Color de Fondo</label>
                        <input type="color" name="bg_color" id="bg_color" class="form-control form-control-color" value="#ffffff" title="Elige un color de fondo">
                    </div>

                    <!--Rareza de la carta-->
                    <div class="col-12">
                        <label for="rarity" class="form-label text-white">Rareza</label>
                        <select name="rarity" id="rarity" class="form-select">
                            <option value="">Selecciona...</option>
                            @foreach([
                            'Common', 'Uncommon', 'Rare', 'Rare Holo', 'Promo',
                            'Rare Holo EX', 'Rare Ultra', 'Rare Holo GX', 'Rare Rainbow',
                            'Rare Shiny GX', 'Rare Secret', 'Rare Holo V', 'Rare Holo VMAX'
                            ] as $rarity)
                            <option value="{{ $rarity }}">{{ $rarity }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!--Debilidades-->
                    <div class="col-md-6">
                        <label for="weakness_type" class="form-label text-white">Debilidad</label>
                        <div class="input-group">
                            <select name="weakness_type" id="weakness_type" class="form-select">
                                <option value="">Ninguna</option>
                                @foreach(['Fire', 'Water', 'Grass', 'Lightning', 'Psychic', 'Fighting', 'Darkness', 'Metal', 'Fairy', 'Dragon'] as $type)
                                <option value="{{ strtolower($type) }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="weakness_amount" id="weakness_amount" class="form-control" placeholder="Cantidad" min="0" max="40" style="max-width: 100px;">
                        </div>
                    </div>

                    <!--Resistencia-->
                    <div class="col-md-6">
                        <label for="resistance_type" class="form-label text-white">Resistencia</label>
                        <div class="input-group">
                            <select name="resistance_type" id="resistance_type" class="form-select">
                                <option value="">Ninguna</option>
                                @foreach(['Fire', 'Water', 'Grass', 'Lightning', 'Psychic', 'Fighting', 'Darkness', 'Metal', 'Fairy', 'Dragon'] as $type)
                                <option value="{{ strtolower($type) }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="resistance_amount" id="resistance_amount" class="form-control" placeholder="Cantidad" min="0" max="20" style="max-width: 100px;">
                        </div>
                    </div>

                    <!--Vida o HP-->
                    <div class="col-12">
                        <label for="hp" class="form-label text-white">HP</label>
                        <input type="number" name="hp" id="hp" class="form-control" placeholder="Ej. 120" required>
                    </div>

                    <!--Descripcion de la carta-->
                    <div class="col-12">
                        <label for="description" class="form-label text-white">Descripción</label>
                        <input type="text" name="description" id="description" class="form-control" placeholder="Texto de la carta">
                    </div>

                    <!--Subida de la imagen-->
                    <div class="col-12">
                        <label for="image" class="form-label text-white">Imagen</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    </div>

                    <!--Botones para limpiar los campos y generar la carta-->
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                        <button type="submit" class="btn btn-primary">Generar Carta</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Vista previa -->
        <div class="col-md-6 text-center">
            <h4 class="text-white">Vista Previa:</h4>
            <img id="preview-image" src="{{ asset('images/templates/pokemoncard1.png') }}" alt="Vista previa" class="img-fluid mt-3" style="max-width: 350px; border: 1px solid #ccc; border-radius: 8px;">
        </div>
    </div>

    <!-- Resultado después de enviar -->
    @if(isset($image))
    <div class="row mt-5 justify-content-center">
        <div class="col-md-6 text-center">
            <h4 class="text-white">Carta Generada:</h4>
            <img src="{{ $image }}" alt="Carta generada" class="img-fluid mt-3" style="max-width: 350px;">
        </div>
    </div>
    @endif
</div>

@endsection
@section('scripts')
@parent
@vite('resources/js/card-preview.js')
@endsection