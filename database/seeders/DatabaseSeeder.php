<?php

namespace Database\Seeders;

use App\Models\Carrera;
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
        // Catálogo de facultades y carreras
        $this->call(FacultadCarreraSeeder::class);

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
            'cedula' => '1710034065',
            'username' => 'estudiante',
            'email' => 'juan.perez@example.com',
            'carrera_id' => Carrera::where('nombre', 'Ingeniería de Software')->value('id'),
            'semestre' => 5,
            'password' => 'estudiante123',
        ]);

        // Más estudiantes de ejemplo (contraseña: password)
        Estudiante::factory(7)->create();
    }
}
