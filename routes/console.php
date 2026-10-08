<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

// Muestra cómo quedan guardadas las contraseñas en la base de datos (hash bcrypt)
Artisan::command('usuarios:listar', function () {
    $this->table(
        ['ID', 'Usuario', 'Contraseña almacenada (hash)'],
        User::all(['id', 'username', 'password'])->map->only('id', 'username', 'password'),
    );
})->purpose('Lista los usuarios con su contraseña cifrada');

// Crea un usuario nuevo para iniciar sesión
Artisan::command('usuarios:crear {username} {password} {--name=}', function (string $username, string $password) {
    User::create([
        'name' => $this->option('name') ?? $username,
        'username' => $username,
        'email' => "{$username}@example.com",
        'password' => Hash::make($password),
    ]);

    $this->info("Usuario '{$username}' creado.");
})->purpose('Crea un usuario con contraseña cifrada');
