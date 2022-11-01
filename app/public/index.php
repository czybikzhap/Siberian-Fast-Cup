<?php

use App\Controller\UserController;
use App\Entity\User;
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

//    /** @var UserController $userIdentityRepository */
//    $userIdentityRepository = $entityManager->getRepository(UserIdentity::class);

    $connection = $entityManager->getConnection();
    return new UserController($userRepository, $userRepository, $connection);
});

AppFactory::setContainer($container);
$app = AppFactory::create();

$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();