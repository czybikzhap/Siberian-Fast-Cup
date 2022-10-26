<?php

return [
    'settings' => [
        'db'=> [
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'host' => 'db',
            'password' => getenv('DB_PASSWORD')
        ],
        'local' => []
    ],
    'logger' => function ($c) {
        $logger = new \Monolog\Logger('my_logger');
        $file_handle = new Monolog\Handler\StreamHandler('../logs/app.log');
        $logger->pushHandler($file_handle);
        return $logger;
    }
];
