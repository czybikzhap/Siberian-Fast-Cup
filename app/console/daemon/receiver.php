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
}catch (Exception $exception){
    print_r($exception->getMessage());
}
