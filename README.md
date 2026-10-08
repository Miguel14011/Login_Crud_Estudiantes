<h1 align="center">🎓 Gestión de Estudiantes</h1>

<p align="center">
  Aplicación web <strong>CRUD con Login</strong> construida con el patrón <strong>MVC</strong> usando <strong>Laravel + React</strong>.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/STATUS-TERMINADO-brightgreen" alt="Estado: terminado">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/React-19-61DAFB?logo=react&logoColor=black" alt="React 19">
  <img src="https://img.shields.io/badge/Inertia.js-3-9553E9" alt="Inertia.js 3">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/tests-21%20pasando-brightgreen" alt="Tests">
</p>

<p align="center">
  <img src="docs/screenshots/03-listado.png" alt="Listado de estudiantes" width="800">
</p>

---

## 📑 Índice

- [Descripción del proyecto](#-descripción-del-proyecto)
- [Estado del proyecto](#-estado-del-proyecto)
- [Funcionalidades y demostración](#-funcionalidades-y-demostración)
- [Arquitectura MVC](#-arquitectura-mvc)
- [Seguridad: login, rutas protegidas y cifrado](#-seguridad-login-rutas-protegidas-y-cifrado)
- [Acceso al proyecto (instalación)](#-acceso-al-proyecto)
- [Pruebas automáticas](#-pruebas-automáticas)
- [Tecnologías utilizadas](#-tecnologías-utilizadas)
- [Estructura de carpetas](#-estructura-de-carpetas)

---

## 📝 Descripción del proyecto

Aplicación para **registrar, consultar, editar y eliminar estudiantes** (nombre, apellido, correo, carrera y semestre).

La sección de gestión está **protegida por un sistema de autenticación**. Solo puede entrar quien inicie sesión con usuario y contraseña. Si alguien intenta abrir cualquier URL del CRUD sin sesión, el servidor lo redirige al login.

El objetivo es aplicar en un mismo proyecto:

- El patrón de arquitectura **Modelo – Vista – Controlador (MVC)**.
- Las cuatro operaciones **CRUD**: Crear, Leer, Actualizar y Eliminar.
- **Autenticación** con contraseñas cifradas y protección de rutas.

## 🚦 Estado del proyecto

✅ **Terminado.** Cumple todos los requisitos de la actividad.

## ✨ Funcionalidades y demostración

🎥 **Video demostrativo:** [Ver en YouTube / Loom](https://ENLACE-DEL-VIDEO)

| Funcionalidad | Descripción |
|---|---|
| 📝 **Registro** | Cualquier persona puede crear su cuenta (nombre, usuario, correo y contraseña con confirmación); entra con rol de solo lectura |
| 👥 **Roles** | **Administrador**: CRUD completo y gestión de usuarios. **Usuario**: solo puede ver |
| 🔐 **Iniciar sesión** | Acceso con usuario y contraseña, opción "Recordarme" |
| 🚫 **Rutas protegidas** | Sin sesión, cualquier URL del CRUD redirige a `/login` |
| 📋 **Listar** | Tabla con búsqueda por nombre, correo o carrera, y paginación |
| ➕ **Crear** | Formulario con validaciones (campos obligatorios, correo único, semestre 1-12) |
| 👁️ **Ver detalle** | Ficha completa del estudiante |
| ✏️ **Editar** | Actualización de datos con las mismas validaciones |
| 🗑️ **Eliminar** | Borrado con confirmación previa |
| 🚪 **Cerrar sesión** | Invalida la sesión, limpia el historial del navegador y regresa al login |

### Capturas

| Login | Credenciales incorrectas |
|---|---|
| ![Login](docs/screenshots/01-login.png) | ![Error de login](docs/screenshots/02-login-error.png) |

| Registro con validaciones | Registro exitoso (inicia sesión automáticamente) |
|---|---|
| ![Registro](docs/screenshots/08-registro-validacion.png) | ![Registro exitoso](docs/screenshots/09-registro-exitoso.png) |

| Listado (Leer) | Crear con validaciones |
|---|---|
| ![Listado](docs/screenshots/03-listado.png) | ![Validaciones](docs/screenshots/04-validacion.png) |

| Registro creado | Editar (Actualizar) |
|---|---|
| ![Creado](docs/screenshots/05-creado.png) | ![Editar](docs/screenshots/06-editar.png) |

| Detalle | Usuario con rol de solo lectura |
|---|---|
| ![Detalle](docs/screenshots/07-detalle.png) | ![Solo lectura](docs/screenshots/10-usuario-solo-lectura.png) |

| Usuario sin permiso intenta crear (403) | Admin: gestión de usuarios y roles |
|---|---|
| ![Acceso denegado](docs/screenshots/11-acceso-denegado.png) | ![Usuarios](docs/screenshots/12-usuarios-admin.png) |

## 🏛️ Arquitectura MVC

Laravel y React se conectan con **[Inertia.js](https://inertiajs.com)**. Así todo vive en **un solo proyecto MVC**: el controlador de Laravel devuelve un componente React como si fuera una vista. No hace falta una API REST aparte ni tokens; se usan las rutas y la sesión de Laravel.

```mermaid
flowchart LR
    U([Navegador]) -->|GET /estudiantes| R[routes/web.php<br/>middleware auth]
    R -->|sin sesión| L[/Redirige a /login/]
    R -->|con sesión| C[EstudianteController<br/>CONTROLADOR]
    C <-->|Eloquent| M[(Estudiante<br/>MODELO)]
    C -->|Inertia::render| V[Estudiantes/Index.jsx<br/>VISTA React]
    V --> U
```

| Capa | Archivos |
|---|---|
| **Modelo** | [`app/Models/Estudiante.php`](app/Models/Estudiante.php), [`app/Models/User.php`](app/Models/User.php), migraciones en [`database/migrations/`](database/migrations/) |
| **Vista** | [`resources/js/Pages/`](resources/js/Pages/): `Auth/Login.jsx`, `Auth/Register.jsx`, `Estudiantes/Index.jsx`, `Create.jsx`, `Edit.jsx`, `Show.jsx`, `Form.jsx`, `Usuarios/Index.jsx`, `Error.jsx`; layout en [`resources/js/Layouts/AppLayout.jsx`](resources/js/Layouts/AppLayout.jsx) |
| **Controlador** | [`EstudianteController.php`](app/Http/Controllers/EstudianteController.php) (CRUD), [`AuthController.php`](app/Http/Controllers/AuthController.php) (registro, login y logout) y [`UsuarioController.php`](app/Http/Controllers/UsuarioController.php) (gestión de roles) |
| **Rutas** | [`routes/web.php`](routes/web.php) |

### Rutas públicas (solo sin sesión)

| Método | URL | Acción |
|---|---|---|
| `GET` / `POST` | `/login` | `showLogin` / `login` |
| `GET` / `POST` | `/register` | `showRegister` / `register` |

### Rutas protegidas (requieren sesión)

| Operación | Método | URL | Acción | Quién puede |
|---|---|---|---|---|
| Leer (listado) | `GET` | `/estudiantes` | `index` | Todos |
| Leer (detalle) | `GET` | `/estudiantes/{id}` | `show` | Todos |
| Crear (formulario) | `GET` | `/estudiantes/create` | `create` | Solo admin |
| Crear (guardar) | `POST` | `/estudiantes` | `store` | Solo admin |
| Actualizar (formulario) | `GET` | `/estudiantes/{id}/edit` | `edit` | Solo admin |
| Actualizar (guardar) | `PUT` | `/estudiantes/{id}` | `update` | Solo admin |
| Eliminar | `DELETE` | `/estudiantes/{id}` | `destroy` | Solo admin |
| Ver usuarios | `GET` | `/usuarios` | `index` | Solo admin |
| Cambiar rol | `PATCH` | `/usuarios/{id}/rol` | `updateRole` | Solo admin |

## 🔒 Seguridad: login, rutas protegidas y cifrado

### Rutas protegidas

```php
// routes/web.php
Route::middleware(['auth', 'inertia.encrypt'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Solo administradores: si otro usuario lo intenta, responde 403
    Route::middleware('can:admin')->group(function () {
        Route::resource('estudiantes', EstudianteController::class)->except(['index', 'show']);
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::patch('/usuarios/{user}/rol', [UsuarioController::class, 'updateRole']);
    });

    // Cualquier usuario autenticado: solo lectura
    Route::resource('estudiantes', EstudianteController::class)->only(['index', 'show']);
});
```

El middleware `auth` se ejecuta **en el servidor**. Si no hay sesión, Laravel responde con una redirección a `/login` antes de llegar al controlador. Por eso escribir la URL a mano en el navegador tampoco funciona. Además, las rutas `/login` y `/register` usan el middleware `guest`: un usuario ya autenticado es enviado directamente al CRUD.

Al **cerrar sesión** se limpia el historial del navegador (`inertia.encrypt` + `Inertia::clearHistory()`), así que el botón "Atrás" tampoco muestra datos protegidos.

### Roles: administrador y usuario

| Rol | Cómo se obtiene | Permisos |
|---|---|---|
| **Administrador** | Cuenta `admin` del seeder, o asignado por otro admin | CRUD completo + página **Usuarios** para cambiar roles |
| **Usuario** | Cualquiera que se registre en `/register` | **Solo lectura**: ver el listado y el detalle de los estudiantes |

- El permiso se define con un **Gate** en [`AppServiceProvider.php`](app/Providers/AppServiceProvider.php): `Gate::define('admin', fn (User $user) => $user->isAdmin())`.
- Las rutas de escritura usan `->middleware('can:admin')`. Si un usuario sin permiso escribe la URL a mano (por ejemplo `/estudiantes/create`), el **servidor responde 403** con una página de "Acceso denegado". Ocultar los botones en React es solo comodidad: la protección real está en el backend.
- El campo `role` **no se puede enviar desde el registro**: no está en `$fillable`, así que nadie puede auto-asignarse administrador.
- Un administrador no puede quitarse su propio rol, para evitar quedarse sin acceso.

### Cifrado de contraseñas

Las contraseñas **nunca se guardan en texto plano**. Se cifran con **bcrypt**, el algoritmo de *hashing* que Laravel usa por defecto:

```php
// app/Models/User.php
protected function casts(): array
{
    return ['password' => 'hashed'];   // cifra automáticamente al guardar
}
```

Así se ve la contraseña `admin123` en la base de datos. El hash cambia en cada instalación por el *salt* aleatorio:

```text
$ php artisan usuarios:listar
+----+------------+---------+--------------------------------------------------------------+
| ID | Usuario    | Rol     | Contraseña almacenada (hash)                                 |
+----+------------+---------+--------------------------------------------------------------+
| 1  | admin      | admin   | $2y$12$6gIHAYJ6TFcKlz4fuOZLzugN3OvDSjHzSeIhYEJpXjYII4yxinIEK |
| 2  | Miguel1209 | usuario | $2y$12$Tx/Q72NkYBUKyrenpFB8rehzJCJNleVjXByITGoaT1v6hedFiTdQq |
+----+------------+---------+--------------------------------------------------------------+
```

> **¿Por qué bcrypt y no md5?** md5 se diseñó para ser rápido, por lo que hoy se pueden probar miles de millones de contraseñas por segundo. Además, sin *salt*, la misma contraseña siempre produce el mismo hash y se puede buscar en tablas precalculadas. **bcrypt** es lento a propósito (factor de costo `12`, el `$12$` del hash) y añade un *salt* aleatorio a cada contraseña, así que dos usuarios con la misma clave tienen hashes distintos.

Al iniciar sesión, `Auth::attempt()` cifra la contraseña escrita y la compara con el hash guardado:

```php
// app/Http/Controllers/AuthController.php
if (! Auth::attempt($credentials, $request->boolean('remember'))) {
    throw ValidationException::withMessages(['username' => 'Usuario o contraseña incorrectos.']);
}
$request->session()->regenerate();
```

### Otras medidas

- 🔁 **Regeneración de sesión** al iniciar sesión, contra la fijación de sesión.
- 🛡️ **Protección CSRF** en todos los formularios.
- ⏱️ **Límite de intentos**: máximo 5 intentos de login por minuto (`throttle:5,1`).
- ✅ **Validación en el servidor** de todos los datos del CRUD.

## 🚀 Acceso al proyecto

### Requisitos

- PHP 8.3 o superior, con las extensiones `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) 20 o superior y npm

### Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/Miguel14011/Login_Crud_Estudiantes.git
cd Login_Crud_Estudiantes

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar el entorno
cp .env.example .env
php artisan key:generate

# 4. Crear la base de datos (SQLite) con datos de ejemplo
touch database/database.sqlite        # en Windows: type nul > database\database.sqlite
php artisan migrate --seed

# 5. Compilar el frontend y levantar el servidor
npm run build
php artisan serve
```

Abre **http://127.0.0.1:8000** e ingresa con:

| Usuario | Contraseña | Rol |
|---|---|---|
| `admin` | `admin123` | Administrador |

O crea tu propia cuenta desde **Regístrate** en la pantalla de login (`/register`). Las cuentas nuevas tienen rol **usuario** (solo lectura); el admin puede cambiarlo desde la página **Usuarios**.

> 💡 Mientras desarrollas, usa `npm run dev` en otra terminal para ver los cambios de React al instante.

### Comandos útiles

| Comando | Descripción |
|---|---|
| `php artisan usuarios:listar` | Muestra los usuarios con su rol y su contraseña cifrada |
| `php artisan usuarios:crear juan clave123` | Crea un usuario de solo lectura (agrega `--admin` para que sea administrador) |
| `php artisan migrate:fresh --seed` | Reinicia la base de datos con los datos de ejemplo |

## 🧪 Pruebas automáticas

```bash
php artisan test
```

21 pruebas en [`tests/Feature/`](tests/Feature/) verifican que:

- Cada URL protegida (`index`, `create`, `show`, `edit`, `store`, `update`, `destroy`) **redirige a `/login` sin sesión**.
- La contraseña se guarda **cifrada con bcrypt**.
- El login acepta credenciales válidas y rechaza las incorrectas.
- Un usuario autenticado no ve el login y puede cerrar sesión.
- Un usuario normal recibe **403** en crear, editar, eliminar y `/usuarios`; el registro no permite auto-asignarse admin; el admin puede cambiar roles pero no el suyo.
- El registro crea el usuario con la contraseña cifrada, inicia sesión y valida usuario y correo únicos y la confirmación de contraseña.
- Funcionan las operaciones CRUD y sus validaciones.

## 🛠️ Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| [Laravel 13](https://laravel.com) | Backend: rutas, controladores, modelos (Eloquent), autenticación y validación |
| [React 19](https://react.dev) | Interfaz de usuario (capa Vista) |
| [Inertia.js](https://inertiajs.com) | Conecta los controladores de Laravel con los componentes React |
| [Tailwind CSS 4](https://tailwindcss.com) | Estilos |
| [Vite](https://vitejs.dev) | Compilación del frontend |
| SQLite | Base de datos |
| PHPUnit | Pruebas automáticas |

## 📂 Estructura de carpetas

```text
Login_Crud_Estudiantes/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php         # Registro / login / logout
│   │   │   ├── EstudianteController.php   # CRUD
│   │   │   └── UsuarioController.php      # Gestión de roles (solo admin)
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php  # Datos compartidos con React (usuario, rol, mensajes)
│   ├── Models/
│   │   ├── Estudiante.php
│   │   └── User.php                       # Rol (admin / usuario) e isAdmin()
│   └── Providers/AppServiceProvider.php   # Permiso (Gate) 'admin'
├── database/
│   ├── factories/                         # Datos de prueba
│   ├── migrations/                        # Estructura de las tablas
│   └── seeders/DatabaseSeeder.php         # Usuario admin + estudiantes de ejemplo
├── resources/
│   ├── js/
│   │   ├── app.jsx                        # Punto de entrada de React
│   │   ├── Layouts/AppLayout.jsx
│   │   └── Pages/
│   │       ├── Auth/{Login,Register}.jsx
│   │       ├── Estudiantes/{Index,Create,Edit,Show,Form}.jsx
│   │       ├── Usuarios/Index.jsx
│   │       └── Error.jsx                  # Páginas 403 / 404
│   └── views/app.blade.php                # Plantilla HTML raíz
├── routes/
│   ├── web.php                            # Rutas públicas y protegidas
│   └── console.php                        # Comandos usuarios:listar / usuarios:crear
└── tests/Feature/                         # Pruebas de login, protección y CRUD
```
