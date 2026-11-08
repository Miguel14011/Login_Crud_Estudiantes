<?php

namespace Tests\Feature;

use App\Models\Carrera;
use App\Models\Estudiante;
use App\Models\Facultad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Relación estudiante -> carrera -> facultad: el formulario usa dropdowns
 * dependientes y el servidor verifica que la carrera pertenezca a la facultad.
 */
class FacultadCarreraTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_endpoint_devuelve_solo_las_carreras_de_la_facultad(): void
    {
        $ingenieria = Facultad::factory()->create();
        $salud = Facultad::factory()->create();
        Carrera::factory()->for($ingenieria)->create(['nombre' => 'Software']);
        Carrera::factory()->for($ingenieria)->create(['nombre' => 'Civil']);
        Carrera::factory()->for($salud)->create(['nombre' => 'Medicina']);

        $this->getJson("/facultades/{$ingenieria->id}/carreras")
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonPath('0.nombre', 'Civil')
            ->assertJsonPath('1.nombre', 'Software')
            ->assertJsonMissing(['nombre' => 'Medicina']);
    }

    public function test_facultad_inexistente_da_404(): void
    {
        $this->getJson('/facultades/999/carreras')->assertNotFound();
    }

    public function test_los_formularios_reciben_la_lista_de_facultades(): void
    {
        Facultad::factory(3)->create();

        $this->get('/register')->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Register', false)
            ->has('facultades', 3));

        $this->actingAs(User::factory()->admin()->create())
            ->get('/estudiantes/create')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Estudiantes/Create', false)
                ->has('facultades', 3));
    }

    public function test_editar_precarga_la_carrera_y_su_facultad(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get("/estudiantes/{$estudiante->id}/edit")
            ->assertInertia(fn (Assert $page) => $page
                ->where('estudiante.carrera_id', $estudiante->carrera_id)
                ->where('estudiante.carrera.facultad_id', $estudiante->carrera->facultad_id)
                ->has('facultades'));
    }

    public function test_el_servidor_rechaza_una_carrera_que_no_pertenece_a_la_facultad(): void
    {
        $ingenieria = Facultad::factory()->create();
        $medicina = Carrera::factory()->create(); // de otra facultad

        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', [
                'nombre' => 'Ana',
                'apellido' => 'Pérez',
                'cedula' => '1710034065',
                'username' => 'anap',
                'email' => 'ana@example.com',
                'facultad_id' => $ingenieria->id,
                'carrera_id' => $medicina->id,
                'semestre' => 3,
                'password' => 'secreto123',
                'password_confirmation' => 'secreto123',
            ])
            ->assertSessionHasErrors(['carrera_id' => 'La carrera seleccionada no pertenece a la facultad elegida.']);

        $this->assertSame(0, Estudiante::count());
    }

    public function test_el_estudiante_se_guarda_con_la_clave_foranea_de_la_carrera(): void
    {
        $carrera = Carrera::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', [
                'nombre' => 'Ana',
                'apellido' => 'Pérez',
                'cedula' => '1710034065',
                'username' => 'anap',
                'email' => 'ana@example.com',
                'facultad_id' => $carrera->facultad_id,
                'carrera_id' => $carrera->id,
                'semestre' => 3,
                'password' => 'secreto123',
                'password_confirmation' => 'secreto123',
            ])
            ->assertSessionHasNoErrors();

        $estudiante = Estudiante::where('username', 'anap')->firstOrFail();
        $this->assertTrue($estudiante->carrera->is($carrera));
        $this->assertTrue($estudiante->carrera->facultad->is($carrera->facultad));
    }

    public function test_el_listado_muestra_carrera_y_facultad(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/estudiantes')
            ->assertInertia(fn (Assert $page) => $page
                ->where('estudiantes.data.0.carrera.nombre', $estudiante->carrera->nombre)
                ->where('estudiantes.data.0.carrera.facultad.nombre', $estudiante->carrera->facultad->nombre));
    }
}
