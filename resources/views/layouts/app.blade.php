<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PokePedia</title>
    <!--Enlaces de bootstrap de los estilos-->
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

        .card-img-top {
            max-height: 300px;
            object-fit: contain;
            padding-top: 15px;
        }

        /*Zoon de la carta y sombras cuando zoomea*/
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.72);
        }

        .list-group-item {
            transition: transform 0.3s;
            background: #fff3cd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .list-group:hover {
            transform: scale(1.03);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.72);
            border-radius: 12px;
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

        /*Contenedor de la vista con un filtro grisaceo*/
        .container {
            width: 90%;
            max-width: 1200px;
            padding: 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
        }

        .switch {
            font-size: 17px;
            position: relative;
            display: inline-block;
            width: 4em;
            height: 2.2em;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #2a2a2a;
            transition: 0.4s;
            border-radius: 30px;
            overflow: hidden;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 1.2em;
            width: 1.2em;
            border-radius: 20px;
            left: 0.5em;
            bottom: 0.5em;
            transition: 0.4s;
            transition-timing-function: cubic-bezier(0.81, -0.04, 0.38, 1.5);
            box-shadow: inset 8px -4px 0px 0px #fff;
        }

        .switch input:checked+.slider {
            background-color: #00a6ff;
        }

        .switch input:checked+.slider:before {
            transform: translateX(1.8em);
            box-shadow: inset 15px -4px 0px 15px #ffcf48;
        }

        .star {
            background-color: #fff;
            border-radius: 50%;
            position: absolute;
            width: 5px;
            transition: all 0.4s;
            height: 5px;
        }

        .star_1 {
            left: 2.5em;
            top: 0.5em;
        }

        .star_2 {
            left: 2.2em;
            top: 1.2em;
        }

        .star_3 {
            left: 3em;
            top: 0.9em;
        }

        .switch input:checked~.slider .star {
            opacity: 0;
        }

        .cloud {
            width: 3.5em;
            position: absolute;
            bottom: -1.4em;
            left: -1.1em;
            opacity: 0;
            transition: all 0.4s;
        }

        .switch input:checked~.slider .cloud {
            opacity: 1;
        }
    </style>
</head>

<body>
    <!--Swicth para el modo oscuro/claro-->
    <div class="position-fixed top-0 end-0 p-3 d-flex justify-content-end" style="z-index: 999;">
        <label class="switch">
            <input type="checkbox" id="checkbox">
            <span class="slider">
                <span class="star star_1"></span>
                <span class="star star_2"></span>
                <span class="star star_3"></span>
                <svg class="cloud" viewBox="0 0 64 64">
                    <path d="M20 50h24a14 14 0 0 0 0-28 20 20 0 0 0-40 4 14 14 0 0 0 16 24z" fill="#fff" />
                </svg>
            </span>
        </label>
    </div>

    <div class="container">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //Al cargar la página, verifica el estado del switch en localStorage
        window.addEventListener('DOMContentLoaded', (event) => {
            const checkbox = document.getElementById('checkbox');
            const body = document.body;
            const overlay = document.getElementById('dark-overlay');

            //Verifica si el estado del switch está guardado en localStorage
            const backgroundState = localStorage.getItem('backgroundState');

            //Si existe, aplica el fondo correspondiente
            if (backgroundState === 'dark') {
                checkbox.checked = true;
                body.style.background = 'linear-gradient(135deg, rgb(225, 27, 27), #f09819, rgb(16, 213, 157), rgb(20, 115, 217))';
                body.style.backgroundAttachment = 'fixed';
                body.style.display = 'flex';
                body.style.flexDirection = 'column';
                body.style.alignItems = 'center'
            } else {
                checkbox.checked = false;
                body.style.background = 'linear-gradient(135deg, #2a2a2a, #3e1b6e, #000, #3d0e7b)';
                body.style.backgroundAttachment = 'fixed';
                body.style.display = 'flex';
                body.style.flexDirection = 'column';
                body.style.alignItems = 'center';
            }
        });

        //Escuchar el evento de cambio en el switch
        document.getElementById('checkbox').addEventListener('change', function() {
            //Obtener el body
            const body = document.body;

            //Cambiar el fondo según el estado del switch
            if (this.checked) {
                //Fondo oscuro cuando el switch está activado
                body.style.background = 'linear-gradient(135deg, rgb(225, 27, 27), #f09819, rgb(16, 213, 157), rgb(20, 115, 217))';
                body.style.backgroundAttachment = 'fixed';
                body.style.display = 'flex';
                body.style.flexDirection = 'column';
                body.style.alignItems = 'center'
                //Guardar el estado del fondo en localStorage
                localStorage.setItem('backgroundState', 'dark');
            } else {
                //Fondo original cuando el switch está desactivado
                body.style.background = 'linear-gradient(135deg, #2a2a2a, #3e1b6e, #000, #3d0e7b)';
                body.style.backgroundAttachment = 'fixed';
                body.style.display = 'flex';
                body.style.flexDirection = 'column';
                body.style.alignItems = 'center';
                //Guardar el estado del fondo en localStorage
                localStorage.setItem('backgroundState', 'light');
            }
        });
    </script>

    @yield('scripts')
</body>

</html>