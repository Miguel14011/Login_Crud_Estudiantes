<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facultades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        // Cada carrera pertenece a una facultad (FK facultad_id -> facultades.id)
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facultad_id')->constrained('facultades')->cascadeOnDelete();
            $table->string('nombre');
            $table->timestamps();

            $table->unique(['facultad_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carreras');
        Schema::dropIfExists('facultades');
    }
};
