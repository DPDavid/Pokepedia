<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
</head>

<body>
    <main class="container align-center p-5">
        <form method="POST" action="{{route('iniciar-sesion')}}">
            @csrf
            <div class="mb-3">
                <label for="emailInput" class="form-label">Email</label>
                <input type="email" class="form-control" id="emailInput"
                    name="email" required autocomplete="disable">
            </div>
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Password</label>
                <input type="password" class="form-control" id="passwordInput"
                    name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberCheck" name="remember">
                <label class="form-check-label" for="rememberCheck">Mantener sesión iniciada</label>
            </div>
            <div>
                <p>¿No tiene cuenta?<a href="{{route('registro')}}">Regístrate</a></p>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="{{ route('pokemon.index') }}" class="btn btn-secondary">Volver al principio</a>
            </div>
        </form>
    </main>
</body>

</html>