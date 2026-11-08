<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

// Muestra cómo quedan guardadas las contraseñas en la base de datos (hash bcrypt)
Artisan::command('usuarios:listar', function () {
    $this->table(
        ['ID', 'Usuario', 'Rol', 'Contraseña almacenada (hash)'],
        User::all(['id', 'username', 'role', 'password'])->map->only('id', 'username', 'role', 'password'),
    );
})->purpose('Lista los usuarios con su rol y contraseña cifrada');

// Usado al desplegar: carga los datos de ejemplo solo la primera vez (base vacía)
Artisan::command('app:sembrar-si-vacia', function () {
    if (User::exists()) {
        $this->info('La base ya tiene datos: no se siembra.');

        return;
    }

    $this->call('db:seed', ['--force' => true]);
})->purpose('Ejecuta los seeders solo si la base de datos está vacía');
