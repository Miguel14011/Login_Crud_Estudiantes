<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstudianteRequest;
use App\Models\Estudiante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador de autenticación: registro de estudiantes, login con
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

        // Directo a la página de su rol (sin pasar por "/"): una petición menos
        $destino = $request->user()->isAdmin() ? route('estudiantes.index') : route('perfil');

        return redirect()->intended($destino);
    }

    public function showRegister(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Solo se pueden registrar estudiantes: el rol 'estudiante' lo asigna el modelo.
     */
    public function register(EstudianteRequest $request): RedirectResponse
    {
        // El cast 'hashed' del modelo cifra la contraseña con bcrypt
        $estudiante = Estudiante::create($request->datos());

        Auth::login($estudiante);
        $request->session()->regenerate();

        return redirect()->route('perfil')
            ->with('success', "¡Bienvenido, {$estudiante->nombre}! Tu cuenta fue creada.");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Borra del navegador las páginas protegidas ya visitadas
        Inertia::clearHistory();

        return redirect()->route('login');
    }
}
