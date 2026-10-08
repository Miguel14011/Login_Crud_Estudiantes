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

        $this->get('/estudiantes')->assertRedirect('/login');
        $this->get('/estudiantes/create')->assertRedirect('/login');
        $this->get("/estudiantes/{$estudiante->id}")->assertRedirect('/login');
        $this->get("/estudiantes/{$estudiante->id}/edit")->assertRedirect('/login');
        $this->post('/estudiantes', [])->assertRedirect('/login');
        $this->put("/estudiantes/{$estudiante->id}", [])->assertRedirect('/login');
        $this->delete("/estudiantes/{$estudiante->id}")->assertRedirect('/login');

        $this->assertDatabaseHas('estudiantes', ['id' => $estudiante->id]);
    }

    public function test_la_contrasena_se_guarda_cifrada_con_bcrypt(): void
    {
        $user = User::factory()->create(['password' => 'admin123']);
        $guardada = $user->getRawOriginal('password');

        $this->assertNotSame('admin123', $guardada);
        $this->assertStringStartsWith('$2y$', $guardada);
        $this->assertTrue(Hash::check('admin123', $guardada));
    }

    public function test_login_con_credenciales_validas(): void
    {
        User::factory()->create(['username' => 'admin', 'password' => 'admin123']);

        $this->post('/login', ['username' => 'admin', 'password' => 'admin123'])
            ->assertRedirect('/estudiantes');

        $this->assertAuthenticated();
    }

    public function test_login_con_credenciales_invalidas(): void
    {
        User::factory()->create(['username' => 'admin', 'password' => 'admin123']);

        $this->post('/login', ['username' => 'admin', 'password' => 'incorrecta'])
            ->assertSessionHasErrors(['username' => 'Usuario o contraseña incorrectos.']);

        $this->assertGuest();
    }

    public function test_usuario_autenticado_no_ve_el_login_y_puede_cerrar_sesion(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/login')->assertRedirect('/estudiantes');
        $this->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
    }
}
