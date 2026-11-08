<?php

namespace App\Http\Controllers;

use App\Models\Facultad;
use Illuminate\Http\JsonResponse;

/**
 * Datos para los dropdowns dependientes del formulario de estudiante:
 * al escoger una facultad, el navegador pide aquí sus carreras.
 */
class CatalogoController extends Controller
{
    /** GET /facultades/{facultad}/carreras */
    public function carreras(Facultad $facultad): JsonResponse
    {
        return response()->json(
            $facultad->carreras()->orderBy('nombre')->get(['id', 'nombre'])
        );
    }
}
