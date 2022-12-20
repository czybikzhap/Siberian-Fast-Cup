<?php

use App\Controller\AutorizationController;
use App\Controller\GameController;
use App\Controller\MessageController;
use App\Controller\SubscribController;
use App\Controller\UserController;
use App\Entity\Game;
use App\Entity\Message;
use App\Entity\Subscribe;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\messageHistoryRepository;
use App\Repository\MessageRepository;
use App\Repository\SubscribeRepository;
use App\Repository\UserRepository;
use App\Service\LiClient;
use App\Service\QueueService;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use UMA\DIC\Container;

return [
    'logger' => function ($c) {
        $logger = new Logger('my_logger');
        $file_handle = new Monolog\Handler\StreamHandler('../logs/app.log');
        $logger->pushHandler($file_handle);
        return $logger;
    },
    //TODO отсюда нода взять $pdo и принимать при транзакции
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
    },

    SubscribController::class => function (ContainerInterface $container) {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        /** @var SubscribeRepository $subscribRepository */
        $subscribRepository = $entityManager->getRepository(Subscribe::class);

        $connection = $entityManager->getConnection();
        return new SubscribController($userRepository, $subscribRepository, $connection);
    },

    UserController::class => function (ContainerInterface $container) {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        $queueService = $container->get(QueueService::class);

        return new UserController($userRepository, $queueService);
    },

    GameController::class => function (ContainerInterface $container) {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        /** @var GameRepository $gameRepository */
        $gameRepository = $entityManager->getRepository(Game::class);

        $liClient = $container->get(LiClient::class);

        return new GameController($userRepository, $gameRepository, $liClient);
    },

    LiClient::class => function(){
        return new LiClient();
    },

    MessageController::class => function (ContainerInterface $container) {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        /** @var MessageRepository $messageRepository */
        $messageRepository = $entityManager->getRepository(Message::class);

        /** @var messageHistoryRepository $messageRepository */
        $messageHistoryRepository = $entityManager->getRepository(Message::class);

        $connection = $entityManager->getConnection();

        return new MessageController($userRepository, $messageRepository, $messageHistoryRepository, $connection);
    },

    QueueService::class => function(){
        return new QueueService();
    }
];