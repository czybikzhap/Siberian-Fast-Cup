<?php

require __DIR__ . '/../../vendor/autoload.php';

use App\Service\QueueService;

QueueService::receiverMessage();