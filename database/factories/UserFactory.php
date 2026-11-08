<?php

namespace Database\Factories;

use App\Models\Carrera;
use App\Models\User;
use App\Support\Cedula;
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
            'cedula' => fn () => Cedula::generar(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            // Usa una carrera existente del catálogo; si no hay ninguna, crea una
            'carrera_id' => fn () => Carrera::inRandomOrder()->first() ?? Carrera::factory(),
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
            'cedula' => null,
            'carrera_id' => null,
            'semestre' => null,
        ]);
    }
}
