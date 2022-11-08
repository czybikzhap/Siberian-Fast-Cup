<?php

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
    $app->get('/user/info', [UserController::class, "getUserInfo"]);

//    $app->group('/users', function (Group $group) {
//        $group->get('', ListUsersAction::class);
//        $group->get('/{id}', ViewUserAction::class);
//    });
};