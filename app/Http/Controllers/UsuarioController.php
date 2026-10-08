<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Gestión de cuentas (solo administradores): listar usuarios y cambiar su rol.
 */
class UsuarioController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Usuarios/Index', [
            'usuarios' => User::orderBy('id')->get(['id', 'name', 'username', 'email', 'role', 'created_at']),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $datos = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USUARIO])],
        ]);

        // Evita que el admin se quite su propio rol y se quede sin acceso
        if ($user->is($request->user())) {
            return back()->withErrors(['role' => 'No puedes cambiar tu propio rol.']);
        }

        $user->forceFill(['role' => $datos['role']])->save();

        return back()->with('success', "Rol de {$user->username} actualizado a {$datos['role']}.");
    }
}
