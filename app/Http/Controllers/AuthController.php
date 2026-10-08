<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador de autenticación: registro de usuarios, login con
 * usuario/contraseña contra la tabla `users` y cierre de sesión.
 */
class AuthController extends Controller
{
    public function showLogin(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Usuario o contraseña incorrectos.',
            ]);
        }

        // Evita ataques de fijación de sesión
        $request->session()->regenerate();

        return redirect()->intended(route('estudiantes.index'));
    }

    public function showRegister(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'alpha_dash', 'min:3', 'max:30', 'unique:users'],
            'email' => ['required', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // El cast 'hashed' del modelo User cifra la contraseña con bcrypt
        $user = User::create($datos);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('estudiantes.index')
            ->with('success', "¡Bienvenido, {$user->name}! Tu cuenta fue creada.");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
