<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
</head>

<body>
    <main class="container align-center p-5">
        <!--Post para enviar los datos-->
        <form method="POST" action="{{route('validar-registro')}}">
            @csrf
            <!--Campo del email-->
            <div class="mb-3">
                <label for="emailInput" class="form-label">Email</label>
                <input type="email" class="form-control" id="emailInput"
                    name="email" required autocomplete="disable">
            </div>
            <!--Campo de la contraseña-->
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Password</label>
                <input type="password" class="form-control" id="passwordInput"
                    name="password" required>
            </div>
            <!--Campo del nombre de usuario-->
            <div class="mb-3">
                <label for="userInput" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="userInput" name="name"
                    required autocomplete="disable">
            </div>
            <!--Boton para registrarse y otro para volver al inicio-->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Registrarse</button>
                <a href="{{ route('pokemon.index') }}" class="btn btn-secondary">Volver al principio</a>
            </div>
        </form>
    </main>
</body>

</html>