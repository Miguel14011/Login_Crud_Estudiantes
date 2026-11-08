<?php

namespace App\Rules;

use App\Support\Cedula;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regla de validación del SERVIDOR para la cédula (dato sensible del sistema).
 * Se ejecuta antes de grabar en la base de datos, aunque alguien desactive
 * JavaScript o envíe la petición directamente (curl, Postman, etc.).
 */
class CedulaEcuatoriana implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $error = Cedula::error((string) $value);

        if ($error !== null) {
            $fail($error);
        }
    }
}
