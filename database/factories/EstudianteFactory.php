<?php

namespace Database\Factories;

use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'carrera' => fake()->randomElement([
                'Ingeniería de Sistemas',
                'Ingeniería Industrial',
                'Administración de Empresas',
                'Contaduría Pública',
                'Psicología',
            ]),
            'semestre' => fake()->numberBetween(1, 10),
        ];
    }
}
