<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Modelo (la "M" de MVC) usado por el CRUD.
 *
 * Usa la MISMA tabla `users`, pero solo ve las filas con role = 'estudiante':
 * el administrador nunca aparece en el listado ni se puede editar desde el CRUD.
 * Hereda de User los campos, el cifrado de la contraseña y la factory.
 */
class Estudiante extends User
{
    protected $table = 'users';

    protected static function booted(): void
    {
        static::addGlobalScope('estudiantes', fn (Builder $query) => $query->where('role', self::ROLE_ESTUDIANTE));

        static::creating(fn (Estudiante $estudiante) => $estudiante->role = self::ROLE_ESTUDIANTE);
    }
}
