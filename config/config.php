<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Ethical Marketplace API',
        'base_path' => '/api',
        'jwt_secret' => 'change_this_to_a_long_random_secret_key',
        'jwt_expiration_seconds' => 86400,
    ],
    'database' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'dbname' => 'ethical_marketplace',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
];
