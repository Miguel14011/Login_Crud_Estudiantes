<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador: admin / admin123
        User::factory()->admin()->create([
            'nombre' => 'Administrador',
            'apellido' => 'General',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        // Estudiante de prueba: estudiante / estudiante123
        Estudiante::factory()->create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'username' => 'estudiante',
            'email' => 'juan.perez@example.com',
            'carrera' => 'Ingeniería de Sistemas',
            'semestre' => 5,
            'password' => 'estudiante123',
        ]);

        // Más estudiantes de ejemplo (contraseña: password)
        Estudiante::factory(7)->create();
    }
}
