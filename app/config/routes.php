<?php

use App\Controller\GameController;
use App\Controller\SubscribController;
use App\Controller\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    //Регистрация пользователей
    $app->post('/signup', [UserController::class, "signUp"]);

    //Вход в личный кабинет
    $app->post('/signin', [UserController::class, "signIn"]);

    //Получение данных о пользователе
    $app->get('/user/info', [UserController::class, "getInfo"]);

    //Редактирование данных о пользователе
    $app->get('/user/edit', [UserController::class, "editIInfo"]);

    //Удалить пользователе
    $app->delete('/user/delete', [UserController::class, "delete"]);

    //Выйти из профиля пользователя
    $app->get('/user/signout', [UserController::class, "signOut"]);

    //подписаться на пользователя (добавить подписку на пользователя)
    $app->post('/user/subscription/add', [SubscribController::class, "addSubscription"]);

    //отписаться от пользователя (удалить подписку)
    $app->post('/user/subscription/delete', [SubscribController::class, "deleteSubscription"]);

    //
    //список подписчиков
    $app->get('/user/subscribers/show', [SubscribController::class, "showSubscribers"]);

    //список подписок
    $app->get('/user/subscriptions/show', [SubscribController::class, "showSubscriptions"]);

    //список партий
    //система очередей
    $app->get('/game/show', [GameController::class, "showGame"]);

//    $app->group('/users', function (Group $group) {
//        $group->get('', ListUsersAction::class);
//        $group->get('/{id}', ViewUserAction::class);
//    });
};