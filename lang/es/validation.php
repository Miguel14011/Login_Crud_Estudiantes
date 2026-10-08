<?php

return [
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
    ],
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'max' => [
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'Este :attribute ya está registrado.',

    'custom' => [
        'username' => [
            'regex' => 'El usuario solo puede tener letras, números, puntos, guiones y guiones bajos (sin espacios).',
        ],
    ],

    'attributes' => [
        'username' => 'usuario',
        'password' => 'contraseña',
        'email' => 'correo electrónico',
    ],
];
