<?php

use App\Controller\GameController;
use App\Controller\MessageController;
use App\Controller\SubscribController;
use App\Controller\UserController;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    //Главная страница
    $app->get('/', function (Request $request, Response $response){
        $response->getBody()->write('Hello');

        return $response;
    });

    //Регистрация пользователей
    $app->post('/signUp', [UserController::class, "signUp"]);

    //Вход в личный кабинет
    $app->post('/signIn', [UserController::class, "signIn"]);

    $app->group('/user', function (RouteCollectorProxy $group){
        //Получение данных о пользователе
        $group->get('/info', [UserController::class, "getInfo"]);

        //Редактирование данных о пользователе
        $group->get('/edit', [UserController::class, "editIInfo"]);

        //Удалить пользователе
        $group->delete('/delete', [UserController::class, "delete"]);

        //Выйти из профиля пользователя
        $group->get('/signOut', [UserController::class, "signOut"]);
    });

    $app->group('/sub', function (RouteCollectorProxy $group){
        //подписаться на пользователя (добавить подписку на пользователя)
        $group->post('/subscriptionAdd', [SubscribController::class, "addSubscription"]);
        //отписаться от пользователя (удалить подписку)
        $group->post('/subscriptionDelete', [SubscribController::class, "deleteSubscription"]);
        //список подписчиков
        $group->get('/subscribersShow', [SubscribController::class, "showSubscribers"]);
        //список подписок
        $group->get('/subscriptionsShow', [SubscribController::class, "showSubscriptions"]);
    });

    //список партий
    $app->get('/game/show', [GameController::class, "showGame"]);

    $app->group('/message', function (RouteCollectorProxy $group){
        //Отправить сообщение пользователю
        $group->post('/send', [MessageController::class, "sendMessage"]);
        //показать сообщения диалога
        $group->get('/show', [MessageController::class, "getMessages"]);
    });
};