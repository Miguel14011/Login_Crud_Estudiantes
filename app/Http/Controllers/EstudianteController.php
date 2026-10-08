<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstudianteRequest;
use App\Models\Estudiante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador (la "C" de MVC): recibe las peticiones, usa el Modelo
 * Estudiante y devuelve la Vista (componente React vía Inertia).
 * Solo el administrador llega aquí (middleware can:admin en las rutas).
 */
class EstudianteController extends Controller
{
    /** READ: listado de todos los estudiantes registrados */
    public function index(Request $request): Response
    {
        $buscar = $request->string('buscar')->trim()->value();

        $estudiantes = Estudiante::query()
            ->when($buscar, fn ($q) => $q->where(fn ($q) => $q
                ->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('apellido', 'like', "%{$buscar}%")
                ->orWhere('username', 'like', "%{$buscar}%")
                ->orWhere('email', 'like', "%{$buscar}%")
                ->orWhere('carrera', 'like', "%{$buscar}%")))
            ->orderBy('apellido')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Estudiantes/Index', [
            'estudiantes' => $estudiantes,
            'filtros' => ['buscar' => $buscar],
        ]);
    }

    /** CREATE: formulario */
    public function create(): Response
    {
        return Inertia::render('Estudiantes/Create');
    }

    /** CREATE: guardar */
    public function store(EstudianteRequest $request): RedirectResponse
    {
        Estudiante::create($request->datos());

        return redirect()->route('estudiantes.index')
            ->with('success', 'Estudiante creado correctamente.');
    }

    /** READ: detalle */
    public function show(Estudiante $estudiante): Response
    {
        return Inertia::render('Estudiantes/Show', [
            'estudiante' => $estudiante,
        ]);
    }

    /** UPDATE: formulario */
    public function edit(Estudiante $estudiante): Response
    {
        return Inertia::render('Estudiantes/Edit', [
            'estudiante' => $estudiante,
        ]);
    }

    /** UPDATE: guardar cambios */
    public function update(EstudianteRequest $request, Estudiante $estudiante): RedirectResponse
    {
        $estudiante->update($request->datos());

        return redirect()->route('estudiantes.index')
            ->with('success', 'Estudiante actualizado correctamente.');
    }

    /** DELETE */
    public function destroy(Estudiante $estudiante): RedirectResponse
    {
        $estudiante->delete();

        return redirect()->route('estudiantes.index')
            ->with('success', 'Estudiante eliminado correctamente.');
    }
}
