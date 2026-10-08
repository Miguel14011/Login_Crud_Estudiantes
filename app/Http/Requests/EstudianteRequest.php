<?php

namespace App\Http\Requests;

use App\Models\Estudiante;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Validación de los datos de un estudiante. Se usa en el registro público
 * y en el CRUD del administrador (crear y editar).
 */
class EstudianteRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Estudiante|null $estudiante */
        $estudiante = $this->route('estudiante');

        return [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'username' => ['required', 'regex:/^[A-Za-z0-9._-]+$/', 'min:3', 'max:30', Rule::unique('users')->ignore($estudiante)],
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($estudiante)],
            'carrera' => ['required', 'string', 'max:150'],
            'semestre' => ['required', 'integer', 'between:1,12'],
            // Al editar, la contraseña es opcional: si se deja vacía se conserva la actual
            'password' => [$estudiante ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * Datos validados, sin la contraseña si se dejó vacía.
     *
     * @return array<string, mixed>
     */
    public function datos(): array
    {
        return array_filter($this->validated(), fn ($valor, $campo) => $campo !== 'password' || filled($valor), ARRAY_FILTER_USE_BOTH);
    }
}
