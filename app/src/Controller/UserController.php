<?php
namespace App\Controller;

use PDO;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Entity\User;
use App\Repository\UserRepository;

class UserController
{

    private ?UserRepository $userRepository;

    public function __construct(UserRepository $userRepository = null)
    {
        $this->userRepository = $userRepository;
    }

    public function signUp (Request $request, Response $response, array $args)
    {
        $params = json_decode($request->getBody()->getContents(), true);

        $lastname = $params['lastname'];#кол-во символов и цифр
        $firstname = $params['firstname'];
        $secondname = $params['secondname'];
        $email = $params['email'];
        $phone = $params['phone'];
        $age = $params['age'];

        if(!isset($params['password'])) {
            $response->getBody()->write("Password not be empty!");

            return $response->withStatus(422);
        }

        $user = new User($lastname, $firstname, $secondname, $email, $params['password'], $phone, $age);
        $this->userRepository->add($user, true);

        return $response->withStatus(201);
    }
}