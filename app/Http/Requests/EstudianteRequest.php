<?php

namespace App\Http\Requests;

use App\Models\Estudiante;
use App\Rules\CedulaEcuatoriana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Validación (en el SERVIDOR) de los datos de un estudiante. Se usa en el
 * registro público y en el CRUD del administrador (crear y editar).
 */
class EstudianteRequest extends FormRequest
{
    /**
     * Limpia la cédula antes de validar: quita espacios y guiones
     * (por ejemplo "171003406-5" -> "1710034065").
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cedula')) {
            $this->merge(['cedula' => preg_replace('/[\s-]/', '', (string) $this->input('cedula'))]);
        }
    }

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
            // 🚨 Dato sensible: algoritmo módulo 10 + única en la base de datos
            'cedula' => ['required', 'string', new CedulaEcuatoriana, Rule::unique('users')->ignore($estudiante)],
            'username' => ['required', 'regex:/^[A-Za-z0-9._-]+$/', 'min:3', 'max:30', Rule::unique('users')->ignore($estudiante)],
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($estudiante)],
            // La facultad solo sirve para filtrar; se valida que exista...
            'facultad_id' => ['required', 'integer', Rule::exists('facultades', 'id')],
            // ...y que la carrera exista Y pertenezca a esa facultad (FK consistente)
            'carrera_id' => ['required', 'integer', Rule::exists('carreras', 'id')->where('facultad_id', $this->integer('facultad_id'))],
            'semestre' => ['required', 'integer', 'between:1,12'],
            // Al editar, la contraseña es opcional: si se deja vacía se conserva la actual
            'password' => [$estudiante ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cedula.unique' => 'Ya existe un estudiante registrado con esta cédula.',
            'carrera_id.exists' => 'La carrera seleccionada no pertenece a la facultad elegida.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'cedula' => 'cédula',
            'facultad_id' => 'facultad',
            'carrera_id' => 'carrera',
        ];
    }

    /**
     * Datos listos para guardar: sin facultad_id (no es columna de users)
     * y sin la contraseña si se dejó vacía.
     *
     * @return array<string, mixed>
     */
    public function datos(): array
    {
        $datos = $this->safe()->except('facultad_id');

        return array_filter($datos, fn ($valor, $campo) => $campo !== 'password' || filled($valor), ARRAY_FILTER_USE_BOTH);
    }
}
