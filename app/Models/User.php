<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo de la tabla `users`: toda persona que inicia sesión
 * (el administrador y los estudiantes).
 *
 * 'role' no está en Fillable: nadie puede asignarse admin desde un formulario.
 */
#[Fillable(['nombre', 'apellido', 'cedula', 'username', 'email', 'carrera_id', 'semestre', 'password'])]
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

    /** @return BelongsTo<Carrera, $this> */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
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
