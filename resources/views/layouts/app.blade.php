<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Pokepedia') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos adicionales si los necesitas -->
    <style>
        body { padding-top: 20px; }
        .card-img-top { max-height: 300px; object-fit: contain; }
    </style>
</head>
<body>
    <div class="container">
        @yield('content') <!-- Aquí se inyectará el contenido de tus vistas -->
    </div>
    <!-- Bootstrap JS (opcional, solo si necesitas funcionalidades JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>