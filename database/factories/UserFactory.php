<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Por defecto crea un estudiante; usa ->admin() para un administrador.
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
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
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'carrera' => fake()->randomElement([
                'Ingeniería de Sistemas',
                'Ingeniería Industrial',
                'Administración de Empresas',
                'Contaduría Pública',
                'Psicología',
            ]),
            'semestre' => fake()->numberBetween(1, 10),
            'role' => User::ROLE_ESTUDIANTE,
            // El cast 'hashed' cifra cada contraseña con su propio salt
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an administrator.
     */
    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_ADMIN,
            'carrera' => null,
            'semestre' => null,
        ]);
    }
}
