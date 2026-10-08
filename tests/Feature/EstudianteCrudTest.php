<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EstudianteCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->admin()->create());
    }

    private function datos(array $extra = []): array
    {
        return [
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'email' => 'ana@example.com',
            'carrera' => 'Ingeniería de Sistemas',
            'semestre' => 3,
            ...$extra,
        ];
    }

    public function test_listar(): void
    {
        Estudiante::factory(3)->create();

        $this->get('/estudiantes')->assertInertia(fn (Assert $page) => $page
            ->component('Estudiantes/Index', false)
            ->has('estudiantes.data', 3));
    }

    public function test_crear(): void
    {
        $this->post('/estudiantes', $this->datos())->assertRedirect('/estudiantes');

        $this->assertDatabaseHas('estudiantes', ['email' => 'ana@example.com']);
    }

    public function test_crear_valida_campos(): void
    {
        $this->post('/estudiantes', $this->datos(['email' => 'no-es-email', 'semestre' => 20]))
            ->assertSessionHasErrors(['email', 'semestre']);

        $this->assertDatabaseCount('estudiantes', 0);
    }

    public function test_ver(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->get("/estudiantes/{$estudiante->id}")->assertInertia(fn (Assert $page) => $page
            ->component('Estudiantes/Show', false)
            ->where('estudiante.id', $estudiante->id));
    }

    public function test_actualizar(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->put("/estudiantes/{$estudiante->id}", $this->datos(['nombre' => 'Lucía']))
            ->assertRedirect('/estudiantes');

        $this->assertSame('Lucía', $estudiante->fresh()->nombre);
    }

    public function test_eliminar(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->delete("/estudiantes/{$estudiante->id}")->assertRedirect('/estudiantes');

        $this->assertModelMissing($estudiante);
    }
}
