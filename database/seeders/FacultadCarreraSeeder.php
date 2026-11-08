<?php

namespace Database\Seeders;

use App\Models\Facultad;
use Illuminate\Database\Seeder;

/** Catálogo de facultades y sus carreras (datos de ejemplo). */
class FacultadCarreraSeeder extends Seeder
{
    public const CATALOGO = [
        'Facultad de Ingeniería y Ciencias Aplicadas' => [
            'Ingeniería de Software',
            'Ingeniería en Tecnologías de la Información',
            'Ingeniería Industrial',
            'Ingeniería Civil',
        ],
        'Facultad de Ciencias Económicas y Administrativas' => [
            'Administración de Empresas',
            'Contabilidad y Auditoría',
            'Finanzas',
            'Mercadotecnia',
        ],
        'Facultad de Ciencias de la Salud' => [
            'Medicina',
            'Enfermería',
            'Odontología',
            'Nutrición y Dietética',
        ],
        'Facultad de Derecho y Ciencias Sociales' => [
            'Derecho',
            'Psicología',
            'Relaciones Internacionales',
        ],
        'Facultad de Comunicación y Artes' => [
            'Comunicación',
            'Diseño Gráfico',
            'Cine',
        ],
    ];

    public function run(): void
    {
        foreach (self::CATALOGO as $facultad => $carreras) {
            $facultad = Facultad::firstOrCreate(['nombre' => $facultad]);

            foreach ($carreras as $carrera) {
                $facultad->carreras()->firstOrCreate(['nombre' => $carrera]);
            }
        }
    }
}
