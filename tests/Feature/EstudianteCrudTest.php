<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EstudianteCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->actingAs($this->admin);
    }

    private function datos(array $extra = []): array
    {
        return [
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'username' => 'anap',
            'email' => 'ana@example.com',
            'carrera' => 'Ingeniería de Sistemas',
            'semestre' => 3,
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123',
            ...$extra,
        ];
    }

    public function test_listar_muestra_los_estudiantes_registrados_pero_no_al_admin(): void
    {
        Estudiante::factory(3)->create();

        $this->get('/estudiantes')->assertInertia(fn (Assert $page) => $page
            ->component('Estudiantes/Index', false)
            ->has('estudiantes.data', 3)
            ->where('estudiantes.data', fn ($data) => collect($data)->doesntContain('id', $this->admin->id)));
    }

    public function test_crear_estudiante_con_contrasena_cifrada(): void
    {
        $this->post('/estudiantes', $this->datos())->assertRedirect('/estudiantes');

        $estudiante = Estudiante::where('username', 'anap')->firstOrFail();
        $this->assertSame(User::ROLE_ESTUDIANTE, $estudiante->role);
        $this->assertTrue(Hash::check('secreto123', $estudiante->password));
    }

    public function test_crear_valida_campos(): void
    {
        $this->post('/estudiantes', $this->datos(['email' => 'no-es-email', 'semestre' => 20, 'password' => '']))
            ->assertSessionHasErrors(['email', 'semestre', 'password']);

        $this->assertSame(0, Estudiante::count());
    }

    public function test_ver(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->get("/estudiantes/{$estudiante->id}")->assertInertia(fn (Assert $page) => $page
            ->component('Estudiantes/Show', false)
            ->where('estudiante.id', $estudiante->id)
            ->missing('estudiante.password'));
    }

    public function test_actualizar_sin_cambiar_la_contrasena(): void
    {
        $estudiante = Estudiante::factory()->create(['password' => 'original123']);

        $this->put("/estudiantes/{$estudiante->id}", $this->datos(['nombre' => 'Lucía', 'password' => '', 'password_confirmation' => '']))
            ->assertRedirect('/estudiantes');

        $estudiante->refresh();
        $this->assertSame('Lucía', $estudiante->nombre);
        $this->assertTrue(Hash::check('original123', $estudiante->password));
    }

    public function test_eliminar(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->delete("/estudiantes/{$estudiante->id}")->assertRedirect('/estudiantes');

        $this->assertModelMissing($estudiante);
    }

    public function test_el_admin_no_se_puede_editar_ni_eliminar_desde_el_crud(): void
    {
        $this->get("/estudiantes/{$this->admin->id}/edit")->assertNotFound();
        $this->delete("/estudiantes/{$this->admin->id}")->assertNotFound();

        $this->assertModelExists($this->admin);
    }
}
