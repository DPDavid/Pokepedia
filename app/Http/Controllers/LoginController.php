<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //---------Funcion para registrarse---------
    public function register(Request $request)
    {
        //Validacion de las variables
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
        ]);

        //Crea la instancia del modelo user
        $user = new User();
        //Asigna a user los valores del formulario
        $user->name  = $request->name;
        $user->email  = $request->email;
        //Encriptado de la contraseña antes de guardar en la base de datos
        $user->password  = Hash::make($request->password);

        //Guarda el usuario en la base de datos
        $user->save();

        //Inicia sesion con el usuario creado
        Auth::login($user);
        //Redirige al usuario a la ruta
        return redirect(route('privada'));
    }

    //---------Funcion para logearse---------
    public function login(Request $request)
    {
        //Credenciales necesarias para iniciar sesion
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        //Verifica si se marcó el checkbox
        $remember = ($request->has('remember') ? true : false);

        //Intenta autenticar al usuario con las credenciales
        if (Auth::attempt($credentials, $remember)) {
            //Si la autenticacion es exitosa, regenera el ID de sesión
            $request->session()->regenerate();

            //Redirige al usuario a la ruta
            return redirect()->intended(route('privada'));
        } else {
            //Si la autenticacion falla, lo redirige de vuelta al login
            return redirect('login');
        }
    }

    //---------Funcion para cerrar sesion---------
    public function logout(Request $request)
    {
        //Cierra sesion del usuario logeado
        Auth::logout();

        //Invalida la sesion actual para no reutilizarlo
        $request->session()->invalidate();
        //Regenera el token CSRF para mayor seguridad
        $request->session()->regenerateToken();

        //Redirige a la vista de login
        return redirect(route('login'));
    }
}
