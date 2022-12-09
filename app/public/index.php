<?php

use App\Controller\UserController;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Slim\Factory\AppFactory;
use UMA\DIC\Container;

require __DIR__ . '/../vendor/autoload.php';

/** @var Container $container */
$container = require __DIR__ . '/../config/bootstrap.php';

$user = new UserController();

AppFactory::setContainer($container);
$app = AppFactory::create();

$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

//$connection = new AMQPStreamConnection(
//    'rabbitmq',
//    5672,
//    'rabbitmq',
//    'rabbitmq'
//);
//$channel = $connection->channel();
//$channel->queue_declare(
//    'push-queue',
//    false,
//    true,
//    false
//);
//
//Функция, которая будет обрабатывать данные, полученные из очереди
//$callback = function($msg) {
//    echo " [x] Received ", $msg->body, "\n";
//};
//
////Уходим слушать сообщения из очереди в бесконечный цикл
//$channel->basic_consume('push-queue',
//    '',
//    false,
//    true,
//    false,
//    false, $callback);
//while(count($channel->callbacks)) {
//    $channel->wait();
//}
//
////Не забываем закрыть соединение и канал
//$channel->close();
//$connection->close();
//Слушаем очередь
$app->run();


