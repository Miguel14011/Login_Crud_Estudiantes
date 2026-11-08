<?php

namespace Tests\Feature;

use App\Models\Carrera;
use App\Models\Estudiante;
use App\Models\User;
use App\Support\Cedula;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * La cédula es el dato sensible del sistema: se valida en el SERVIDOR
 * (no solo con JavaScript) antes de grabarse en la base de datos.
 */
class CedulaTest extends TestCase
{
    use RefreshDatabase;

    private function datos(array $extra = []): array
    {
        $carrera = Carrera::factory()->create();

        return [
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
            ...$extra,
        ];
    }

    public static function cedulasInvalidas(): array
    {
        return [
            'dígito verificador incorrecto' => ['1710034066'],
            'menos de 10 dígitos' => ['171003406'],
            'más de 10 dígitos' => ['17100340655'],
            'con letras' => ['17100340AB'],
            'provincia 00' => ['0010034065'],
            'provincia 25' => ['2510034065'],
            'tercer dígito >= 6' => ['1760034065'],
            'todo ceros' => ['0000000000'],
        ];
    }

    #[DataProvider('cedulasInvalidas')]
    public function test_el_admin_no_puede_grabar_una_cedula_invalida(string $cedula): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', $this->datos(['cedula' => $cedula]))
            ->assertSessionHasErrors('cedula');

        $this->assertSame(0, Estudiante::count());
    }

    public function test_el_admin_graba_una_cedula_valida(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', $this->datos())
            ->assertSessionHasNoErrors()
            ->assertRedirect('/estudiantes');

        $this->assertDatabaseHas('users', ['username' => 'anap', 'cedula' => '1710034065']);
    }

    public function test_la_cedula_se_normaliza_quitando_guiones_y_espacios(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', $this->datos(['cedula' => ' 171003406-5 ']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['username' => 'anap', 'cedula' => '1710034065']);
    }

    public function test_la_cedula_no_se_puede_repetir(): void
    {
        Estudiante::factory()->create(['cedula' => '1710034065']);

        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', $this->datos())
            ->assertSessionHasErrors(['cedula' => 'Ya existe un estudiante registrado con esta cédula.']);
    }

    public function test_al_editar_se_puede_conservar_la_propia_cedula_pero_no_poner_una_invalida(): void
    {
        $estudiante = Estudiante::factory()->create(['cedula' => '1710034065']);
        $this->actingAs(User::factory()->admin()->create());

        $this->put("/estudiantes/{$estudiante->id}", $this->datos(['password' => '', 'password_confirmation' => '']))
            ->assertSessionHasNoErrors();

        $this->put("/estudiantes/{$estudiante->id}", $this->datos(['cedula' => '1710034066', 'password' => '', 'password_confirmation' => '']))
            ->assertSessionHasErrors('cedula');

        $this->assertSame('1710034065', $estudiante->fresh()->cedula);
    }

    public function test_el_registro_publico_tambien_valida_la_cedula(): void
    {
        $this->post('/register', $this->datos(['cedula' => '1234567890']))
            ->assertSessionHasErrors('cedula');

        $this->assertGuest();
    }

    public function test_el_generador_de_la_factory_produce_cedulas_validas(): void
    {
        foreach (range(1, 200) as $_) {
            $this->assertTrue(Cedula::esValida(Cedula::generar()));
        }
    }
}
