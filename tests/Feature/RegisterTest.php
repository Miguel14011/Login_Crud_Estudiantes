<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function datos(array $extra = []): array
    {
        return [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'username' => 'juanp',
            'email' => 'juan@example.com',
            'carrera' => 'Psicología',
            'semestre' => 2,
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123',
            ...$extra,
        ];
    }

    public function test_la_pagina_de_registro_es_publica(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_el_registro_crea_un_estudiante_con_contrasena_cifrada_e_inicia_sesion(): void
    {
        $this->post('/register', $this->datos())->assertRedirect('/perfil');

        $estudiante = Estudiante::where('username', 'juanp')->firstOrFail();
        $this->assertSame(User::ROLE_ESTUDIANTE, $estudiante->role);
        $this->assertSame('Psicología', $estudiante->carrera);
        $this->assertNotSame('secreto123', $estudiante->getRawOriginal('password'));
        $this->assertTrue(Hash::check('secreto123', $estudiante->password));
        $this->assertAuthenticatedAs($estudiante);
    }

    public function test_nadie_puede_registrarse_como_admin(): void
    {
        $this->post('/register', $this->datos(['role' => 'admin']));

        $this->assertSame(User::ROLE_ESTUDIANTE, User::where('username', 'juanp')->value('role'));
    }

    public function test_el_admin_ve_al_estudiante_registrado_en_su_listado(): void
    {
        $this->post('/register', $this->datos());
        $this->post('/logout');

        $this->actingAs(User::factory()->admin()->create())
            ->get('/estudiantes')
            ->assertInertia(fn (Assert $page) => $page
                ->has('estudiantes.data', 1)
                ->where('estudiantes.data.0.username', 'juanp'));
    }

    public function test_el_estudiante_registrado_puede_iniciar_sesion_despues(): void
    {
        $this->post('/register', $this->datos());
        $this->post('/logout');

        $this->post('/login', ['username' => 'juanp', 'password' => 'secreto123'])->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_registro_valida_los_datos(): void
    {
        Estudiante::factory()->create(['username' => 'juanp']);

        $this->post('/register', $this->datos(['password_confirmation' => 'otra', 'email' => 'no-es-correo', 'carrera' => '']))
            ->assertSessionHasErrors(['username', 'email', 'carrera', 'password']);

        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_el_usuario_acepta_puntos_pero_no_espacios(): void
    {
        $this->post('/register', $this->datos(['username' => 'juan perez']))->assertSessionHasErrors('username');

        $this->post('/register', $this->datos(['username' => 'juan.perez']))->assertRedirect('/perfil');
    }

    public function test_usuario_autenticado_no_ve_el_registro(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/register')
            ->assertRedirect('/');
    }
}
