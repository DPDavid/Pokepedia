<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Pokepedia') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /*Estilos para el titulo y transicion cuando pasas el raton*/
        .pokemon-title {
            color: #F8F9FA;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.5s ease-in-out, -webkit-text-fill-color 0.5s ease-in-out;
        }

        /*Hover rojo para el titulo*/
        .pokemon-title:hover {
            color: red;
        }

        /*Sombra para el contenedor de la carta y transicion de zoom*/
        .card {
            transition: transform 0.3s;
            background: #fff3cd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
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
    </style>
</head>

<body>
    <div class="container">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>