<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_estudiante_solo_ve_su_propio_perfil(): void
    {
        $yo = Estudiante::factory()->create();
        Estudiante::factory()->create();

        $this->actingAs($yo)->get('/perfil')->assertInertia(fn (Assert $page) => $page
            ->component('Perfil', false)
            ->where('estudiante.id', $yo->id)
            ->missing('estudiante.password'));
    }

    public function test_el_estudiante_no_puede_entrar_al_crud(): void
    {
        $otro = Estudiante::factory()->create();
        $this->actingAs(Estudiante::factory()->create());

        $this->get('/estudiantes')->assertForbidden();
        $this->get('/estudiantes/create')->assertForbidden();
        $this->get("/estudiantes/{$otro->id}")->assertForbidden();
        $this->get("/estudiantes/{$otro->id}/edit")->assertForbidden();
        $this->post('/estudiantes', [])->assertForbidden();
        $this->put("/estudiantes/{$otro->id}", [])->assertForbidden();
        $this->delete("/estudiantes/{$otro->id}")->assertForbidden();

        $this->assertModelExists($otro);
    }

    public function test_el_403_se_muestra_con_la_pagina_de_error(): void
    {
        $this->actingAs(Estudiante::factory()->create())
            ->get('/estudiantes')
            ->assertForbidden()
            ->assertInertia(fn (Assert $page) => $page->component('Error', false)->where('status', 403));
    }
}
