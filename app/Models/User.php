<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo de la tabla `users`: toda persona que inicia sesión
 * (el administrador y los estudiantes).
 *
 * 'role' no está en Fillable: nadie puede asignarse admin desde un formulario.
 */
#[Fillable(['nombre', 'apellido', 'username', 'email', 'carrera', 'semestre', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_ESTUDIANTE = 'estudiante';

    protected $attributes = [
        'role' => self::ROLE_ESTUDIANTE,
    ];

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'semestre' => 'integer',
            'password' => 'hashed',
        ];
    }
}
