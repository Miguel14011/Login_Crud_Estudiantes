<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_registrado_recibe_el_rol_usuario(): void
    {
        $this->post('/register', [
            'name' => 'Ana',
            'username' => 'ana',
            'email' => 'ana@example.com',
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123',
            'role' => 'admin', // intento de asignarse admin: se ignora
        ]);

        $this->assertSame(User::ROLE_USUARIO, User::where('username', 'ana')->value('role'));
    }

    public function test_el_usuario_normal_solo_puede_ver(): void
    {
        $estudiante = Estudiante::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get('/estudiantes')->assertOk();
        $this->get("/estudiantes/{$estudiante->id}")->assertOk();

        $this->get('/estudiantes/create')->assertForbidden();
        $this->get("/estudiantes/{$estudiante->id}/edit")->assertForbidden();
        $this->post('/estudiantes', [])->assertForbidden();
        $this->put("/estudiantes/{$estudiante->id}", [])->assertForbidden();
        $this->delete("/estudiantes/{$estudiante->id}")->assertForbidden();
        $this->get('/usuarios')->assertForbidden();

        $this->assertModelExists($estudiante);
    }

    public function test_el_403_se_muestra_con_la_pagina_de_error(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/estudiantes/create')
            ->assertForbidden()
            ->assertInertia(fn (Assert $page) => $page->component('Error', false)->where('status', 403));
    }

    public function test_el_admin_ve_los_usuarios_y_cambia_roles(): void
    {
        $admin = User::factory()->admin()->create();
        $usuario = User::factory()->create();

        $this->actingAs($admin)->get('/usuarios')->assertInertia(fn (Assert $page) => $page
            ->component('Usuarios/Index', false)
            ->has('usuarios', 2));

        $this->patch("/usuarios/{$usuario->id}/rol", ['role' => 'admin'])->assertSessionHasNoErrors();
        $this->assertTrue($usuario->fresh()->isAdmin());
    }

    public function test_el_admin_no_puede_quitarse_su_propio_rol(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch("/usuarios/{$admin->id}/rol", ['role' => 'usuario'])
            ->assertSessionHasErrors('role');

        $this->assertTrue($admin->fresh()->isAdmin());
    }
}
