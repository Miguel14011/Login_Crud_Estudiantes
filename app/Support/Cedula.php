<?php

namespace App\Support;

/**
 * Algoritmo oficial de la cédula de identidad ecuatoriana (Registro Civil).
 *
 * Estructura: 10 dígitos
 *   - Dígitos 1-2: código de provincia (01-24, o 30 para ecuatorianos registrados en el exterior)
 *   - Dígito 3:    menor que 6 (persona natural)
 *   - Dígitos 4-9: número secuencial
 *   - Dígito 10:   dígito verificador (módulo 10)
 *
 * Módulo 10: los 9 primeros dígitos se multiplican por 2,1,2,1,2,1,2,1,2;
 * si un producto es mayor que 9 se le resta 9; se suman los resultados y el
 * verificador es lo que le falta a esa suma para llegar a la siguiente decena.
 */
final class Cedula
{
    private const COEFICIENTES = [2, 1, 2, 1, 2, 1, 2, 1, 2];

    /** Devuelve null si la cédula es válida, o el motivo por el que no lo es. */
    public static function error(string $cedula): ?string
    {
        if (! preg_match('/^\d{10}$/', $cedula)) {
            return 'La cédula debe tener exactamente 10 dígitos numéricos.';
        }

        $provincia = (int) substr($cedula, 0, 2);
        if (! (($provincia >= 1 && $provincia <= 24) || $provincia === 30)) {
            return 'Los dos primeros dígitos de la cédula no corresponden a una provincia válida.';
        }

        if ((int) $cedula[2] >= 6) {
            return 'El tercer dígito de la cédula debe ser menor que 6.';
        }

        if (self::digitoVerificador(substr($cedula, 0, 9)) !== (int) $cedula[9]) {
            return 'La cédula no es válida: el dígito verificador no coincide.';
        }

        return null;
    }

    public static function esValida(string $cedula): bool
    {
        return self::error($cedula) === null;
    }

    /** Calcula el décimo dígito a partir de los 9 primeros. */
    public static function digitoVerificador(string $nueveDigitos): int
    {
        $suma = 0;
        foreach (self::COEFICIENTES as $i => $coeficiente) {
            $producto = (int) $nueveDigitos[$i] * $coeficiente;
            $suma += $producto > 9 ? $producto - 9 : $producto;
        }

        return (10 - $suma % 10) % 10;
    }

    /** Genera una cédula válida al azar (para factories y datos de ejemplo). */
    public static function generar(): string
    {
        $base = str_pad((string) random_int(1, 24), 2, '0', STR_PAD_LEFT)
            .random_int(0, 5)
            .str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return $base.self::digitoVerificador($base);
    }
}
