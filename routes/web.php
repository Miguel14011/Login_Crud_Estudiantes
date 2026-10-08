<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstudianteController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/estudiantes');

// Solo para visitantes NO autenticados
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

// Sección protegida: sin sesión iniciada, redirige a /login.
// 'inertia.encrypt' cifra el historial del navegador para que, tras cerrar
// sesión, el botón "Atrás" no muestre datos guardados.
Route::middleware(['auth', 'inertia.encrypt'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('estudiantes', EstudianteController::class);
});
