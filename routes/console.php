<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

// Muestra cómo quedan guardadas las contraseñas en la base de datos (hash bcrypt)
Artisan::command('usuarios:listar', function () {
    $this->table(
        ['ID', 'Usuario', 'Rol', 'Contraseña almacenada (hash)'],
        User::all(['id', 'username', 'role', 'password'])->map->only('id', 'username', 'role', 'password'),
    );
})->purpose('Lista los usuarios con su rol y contraseña cifrada');

// Crea un usuario nuevo para iniciar sesión
Artisan::command('usuarios:crear {username} {password} {--name=} {--admin}', function (string $username, string $password) {
    $user = User::create([
        'name' => $this->option('name') ?? $username,
        'username' => $username,
        'email' => "{$username}@example.com",
        'password' => Hash::make($password),
    ]);

    $user->forceFill(['role' => $this->option('admin') ? User::ROLE_ADMIN : User::ROLE_USUARIO])->save();

    $this->info("Usuario '{$username}' creado con rol {$user->role}.");
})->purpose('Crea un usuario con contraseña cifrada (--admin para darle rol de administrador)');
