<?php
// Database credentials (move to .env or server vars in prod)
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'fleet',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_url' => getenv('BASE_URL') ?: '/fleet/',
        'brand_color' => '#ffcd29',
    ],
    'roles' => [
        'super_admin' => 1,
        'admin' => 2,
        'data_entry' => 3,
        'guest' => 4,
    ],
];