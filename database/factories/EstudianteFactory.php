<?php

namespace Database\Factories;

use App\Models\Estudiante;

/**
 * Mismos datos que UserFactory, pero devuelve modelos Estudiante.
 *
 * @extends UserFactory
 */
class EstudianteFactory extends UserFactory
{
    protected $model = Estudiante::class;
}
