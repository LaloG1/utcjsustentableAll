<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Especifica el origen directamente
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization', 'X-CSRF-Token'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,  // Habilitar el envío de credenciales
];
