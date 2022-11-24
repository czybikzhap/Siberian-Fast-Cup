<?php

use App\Controller\GameController;
use App\Controller\SubscribController;
use App\Controller\UserController;
use App\Entity\Game;
use App\Entity\Subscrib;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\SubscribRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use UMA\DIC\Container;

require __DIR__ . '/../vendor/autoload.php';

/** @var Container $container */
$container = require __DIR__ . '/../config/bootstrap.php';
//TODO : $container->set add dependencies

$container->set(UserController::class, function (ContainerInterface $container) {
    /** @var EntityManager $entityManager */
    $entityManager = $container->get(EntityManager::class);

    /** @var UserRepository $userRepository */
    $userRepository = $entityManager->getRepository(User::class);

    $connection = $entityManager->getConnection();
    return new UserController($userRepository, $connection);
});

$container->set(SubscribController::class, function (ContainerInterface $container) {
    /** @var EntityManager $entityManager */
    $entityManager = $container->get(EntityManager::class);

    /** @var UserRepository $userRepository */
    $userRepository = $entityManager->getRepository(User::class);

    /** @var SubscribRepository $subscribRepository */
    $subscribRepository = $entityManager->getRepository(Subscrib::class);

    $connection = $entityManager->getConnection();
    return new SubscribController($userRepository, $subscribRepository, $connection);
});

$container->set(GameController::class, function (ContainerInterface $container) {
    /** @var EntityManager $entityManager */
    $entityManager = $container->get(EntityManager::class);

    /** @var UserRepository $userRepository */
    $userRepository = $entityManager->getRepository(User::class);

    /** @var GameRepository $gameRepository */
    $gameRepository = $entityManager->getRepository(Game::class);

    $connection = $entityManager->getConnection();
    return new GameController($userRepository, $gameRepository, $connection);
});

$user = new UserController();

AppFactory::setContainer($container);
$app = AppFactory::create();

$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();