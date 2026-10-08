<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_las_urls_protegidas_redirigen_al_login_sin_sesion(): void
    {
        $estudiante = Estudiante::factory()->create();

        $this->get('/')->assertRedirect('/login');
        $this->get('/perfil')->assertRedirect('/login');
        $this->get('/estudiantes')->assertRedirect('/login');
        $this->get('/estudiantes/create')->assertRedirect('/login');
        $this->get("/estudiantes/{$estudiante->id}")->assertRedirect('/login');
        $this->get("/estudiantes/{$estudiante->id}/edit")->assertRedirect('/login');
        $this->post('/estudiantes', [])->assertRedirect('/login');
        $this->put("/estudiantes/{$estudiante->id}", [])->assertRedirect('/login');
        $this->delete("/estudiantes/{$estudiante->id}")->assertRedirect('/login');

        $this->assertModelExists($estudiante);
    }

    public function test_la_contrasena_se_guarda_cifrada_con_bcrypt(): void
    {
        $user = User::factory()->create(['password' => 'admin123']);
        $guardada = $user->getRawOriginal('password');

        $this->assertNotSame('admin123', $guardada);
        $this->assertStringStartsWith('$2y$', $guardada);
        $this->assertTrue(Hash::check('admin123', $guardada));
    }

    public function test_el_admin_inicia_sesion_y_llega_al_crud(): void
    {
        User::factory()->admin()->create(['username' => 'admin', 'password' => 'admin123']);

        $this->post('/login', ['username' => 'admin', 'password' => 'admin123'])->assertRedirect('/');
        $this->get('/')->assertRedirect('/estudiantes');
    }

    public function test_el_estudiante_inicia_sesion_y_llega_a_su_perfil(): void
    {
        Estudiante::factory()->create(['username' => 'juan', 'password' => 'clave1234']);

        $this->post('/login', ['username' => 'juan', 'password' => 'clave1234'])->assertRedirect('/');
        $this->get('/')->assertRedirect('/perfil');
    }

    public function test_login_con_credenciales_invalidas(): void
    {
        User::factory()->admin()->create(['username' => 'admin', 'password' => 'admin123']);

        $this->post('/login', ['username' => 'admin', 'password' => 'incorrecta'])
            ->assertSessionHasErrors(['username' => 'Usuario o contraseña incorrectos.']);

        $this->assertGuest();
    }

    public function test_usuario_autenticado_no_ve_el_login_y_puede_cerrar_sesion(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/login')->assertRedirect('/');
        $this->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
    }
}
