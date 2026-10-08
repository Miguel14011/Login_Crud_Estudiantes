<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Lo que ve cada usuario al iniciar sesión.
 */
class PerfilController extends Controller
{
    /** Página de inicio según el rol: el admin va al CRUD y el estudiante a su perfil */
    public function inicio(Request $request): RedirectResponse
    {
        return $request->user()->isAdmin()
            ? redirect()->route('estudiantes.index')
            : redirect()->route('perfil');
    }

    /** El estudiante ve sus propios datos */
    public function show(Request $request): Response
    {
        return Inertia::render('Perfil', [
            'estudiante' => $request->user(),
        ]);
    }
}
