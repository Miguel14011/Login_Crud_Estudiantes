<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function datos(array $extra = []): array
    {
        return [
            'name' => 'Juan Pérez',
            'username' => 'juanp',
            'email' => 'juan@example.com',
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123',
            ...$extra,
        ];
    }

    public function test_la_pagina_de_registro_es_publica(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_registro_crea_el_usuario_con_contrasena_cifrada_e_inicia_sesion(): void
    {
        $this->post('/register', $this->datos())->assertRedirect('/estudiantes');

        $user = User::where('username', 'juanp')->firstOrFail();
        $this->assertNotSame('secreto123', $user->getRawOriginal('password'));
        $this->assertTrue(Hash::check('secreto123', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_el_usuario_registrado_puede_iniciar_sesion_despues(): void
    {
        $this->post('/register', $this->datos());
        $this->post('/logout');

        $this->post('/login', ['username' => 'juanp', 'password' => 'secreto123'])
            ->assertRedirect('/estudiantes');
        $this->assertAuthenticated();
    }

    public function test_registro_valida_los_datos(): void
    {
        User::factory()->create(['username' => 'juanp']);

        $this->post('/register', $this->datos(['password_confirmation' => 'otra', 'email' => 'no-es-correo']))
            ->assertSessionHasErrors(['username', 'email', 'password']);

        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_usuario_autenticado_no_ve_el_registro(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/register')
            ->assertRedirect('/estudiantes');
    }
}
