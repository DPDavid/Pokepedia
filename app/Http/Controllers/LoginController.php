<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Native\Laravel\Facades\Notification;

class LoginController extends Controller
{
    //Funcion para registrarse
    public function register(Request $request) {
       $user = new User();
       $user-> name  = $request->name;
       $user-> email  = $request->email;
       $user-> password  = Hash::make($request->password);

       $user->save();

       Auth::login($user);
       return redirect(route('privada'));
    }
    
    //Funcion para logearse usando solamente el email y la contraseña
    public function login(Request $request) {
        //Credenciales necesarias para iniciar sesion
        $credentials = [
            'email'=> $request->email,
            'password'=> $request->password,
        ];
    
        //Usa el checkbox de remember para recordar el inicio de sesión o no en la página
        $remember = ($request->has('remember') ? true : false);
        if(Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
    
            return redirect()->intended(route('privada'));
        } else {
            return redirect('login');
        }
    }

    //Funcion para cerrar sesion
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
