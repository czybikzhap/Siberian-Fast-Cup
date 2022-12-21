<?php

require __DIR__ . '/../../vendor/autoload.php';

use App\Service\QueueService;
use Psr\Container\ContainerInterface;

try {
    /** @var ContainerInterface $container */
    $container = require __DIR__ . '/../../config/bootstrap.php';
    /** @var QueueService $queueService */
    $queueService = $container->get(QueueService::class);
    $queueService->receiverMessage();
    echo "все успешно! Жду сообщения";
}catch (Exception $exception){
    echo "$exception";
}
