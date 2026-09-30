<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        // Retorna la vista de login
        return view('auth.login');
    }

    /**
     * Procesa las credenciales de inicio de sesión.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validar campos: email requerido y con formato válido, password requerido
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Regenerar la sesión para prevenir fijación de sesión
            $request->session()->regenerate();

            // Redirigir al dashboard después de login exitoso
            return redirect()->intended('/dashboard');
        }

        // Si falla, regresar con error genérico
        return back()->withErrors(['email' => 'Correo o contraseña incorrectos.']);
    }

    /**
     * Cierra la sesión del usuario actual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Cerrar sesión del usuario autenticado
        Auth::logout();

        // Invalidar la sesión actual y regenerar token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigir a la página de inicio (landing page)
        return redirect()->route('inicio');
    }
}