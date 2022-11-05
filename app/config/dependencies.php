<?php

use UMA\DIC\Container;

return [
    'logger' => function ($c) {
        $logger = new \Monolog\Logger('my_logger');
        $file_handle = new Monolog\Handler\StreamHandler('../logs/app.log');
        $logger->pushHandler($file_handle);
        return $logger;
    },
    'db' => function (Container $c) {
        $db = $c->get('settings')['db'];
        $dsn = "pgsql:host={$db['host']};port=5432;dbname={$db['name']};";
        // make a database connection
        $pdo = new PDO(
            $dsn,
            $db['user'],
            $db['password']
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

];