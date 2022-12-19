<?php

const APP_ROOT = __DIR__;

return [
    'settings' => [
        'db'=> [
            'name' => getenv('DB_NAME') ?: "dbname",
            'user' => getenv('DB_USER') ?: "dbuser",
            'host' => 'db',
            'password' => getenv('DB_PASSWORD') ?: "dbpwd"
        ],

        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => APP_ROOT . '/var/doctrine',
            'metadata_dirs' => [APP_ROOT . '/src/Entity'],
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => 'db',
                'port' => 5432,
                'dbname' => 'dbname',
                'user' => 'dbuser',
                'password' => 'dbpwd',
                'charset' => 'utf-8'
            ]
        ]
    ]
];