<?php

namespace Database\Factories;

use App\Models\Carrera;
use App\Models\Facultad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Carrera>
 */
class CarreraFactory extends Factory
{
    public function definition(): array
    {
        return [
            'facultad_id' => Facultad::factory(),
            'nombre' => 'Carrera de '.fake()->unique()->word(),
        ];
    }
}
