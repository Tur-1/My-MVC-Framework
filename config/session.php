<?php

return [

    'lifetime' => env('SESSION_LIFETIME', 3600),
    'session_name' =>
    strtolower(str_replace('', '_', env('APP_NAME', 'TurFramework') . '_session')),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', '/'),
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
    'samesite' => 'lax',
];
