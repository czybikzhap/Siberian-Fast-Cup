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
    $app->get('/user/info', [UserController::class, "getInfo"]);

    //Редактирование данных о пользователе
    $app->get('/user/edit', [UserController::class, "editIInfo"]);

    //Удалить пользователе
    $app->delete('/user/delete', [UserController::class, "delete"]);

    //Выйти из профиля пользователя
    $app->get('/user/signout', [UserController::class, "signOut"]);

    //подписаться на друга
    $app->post('/user/follower/add', [UserController::class, "addFollower"]);

    //отписаться от друга
    $app->post('/user/follower/delete', [UserController::class, "deleteFollower"]);

    //список друзей
    $app->post('/user/follower/show', [UserController::class, "showFollowers"]);


//    $app->group('/users', function (Group $group) {
//        $group->get('', ListUsersAction::class);
//        $group->get('/{id}', ViewUserAction::class);
//    });
};