<?php

return [
    'secret' => env('JWT_SECRET', env('APP_KEY')),
    'issuer' => env('JWT_ISSUER', env('APP_URL', 'vetehub')),
    'ttl_minutes' => (int) env('JWT_TTL_MINUTES', 1440),
    'remember_ttl_minutes' => (int) env('JWT_REMEMBER_TTL_MINUTES', 43200),
    'cookie_name' => env('JWT_COOKIE_NAME', 'vetehub_token'),
    'cookie_secure' => (bool) env('JWT_COOKIE_SECURE', env('APP_ENV') === 'production'),
    'cookie_samesite' => env('JWT_COOKIE_SAMESITE', 'lax'),
];
