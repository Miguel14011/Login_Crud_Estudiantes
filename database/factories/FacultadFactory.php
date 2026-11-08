<?php

namespace Database\Factories;

use App\Models\Facultad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facultad>
 */
class FacultadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => 'Facultad de '.fake()->unique()->word(),
        ];
    }
}
