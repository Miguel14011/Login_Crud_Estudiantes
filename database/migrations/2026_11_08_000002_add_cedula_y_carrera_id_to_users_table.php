<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * - cedula: dato sensible, única (null para el admin).
 * - carrera_id: FK a carreras, reemplaza al antiguo texto libre `carrera`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula', 10)->nullable()->unique()->after('apellido');
            $table->foreignId('carrera_id')->nullable()->after('email')
                ->constrained('carreras')->restrictOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('carrera');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('carrera')->nullable()->after('email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('carrera_id');
            $table->dropUnique(['cedula']);
            $table->dropColumn('cedula');
        });
    }
};
