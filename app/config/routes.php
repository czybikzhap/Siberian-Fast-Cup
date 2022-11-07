<?php

use App\Controller\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    //Регистрация пользователей
    $app->post('/signup', [UserController::class, "signUp"]);

    $app->post('/signin', [UserController::class, "signIn"]);

//    $app->group('/users', function (Group $group) {
//        $group->get('', ListUsersAction::class);
//        $group->get('/{id}', ViewUserAction::class);
//    });
};