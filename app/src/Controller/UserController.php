<?php
namespace App\Controller;

use PDO;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Headers;
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
    #Регистрация пользователя
    public function signUp (Request $request, Response $response)
    {
        $params = json_decode($request->getBody()->getContents(), true);
        $errors = $this->validateSignUp($params);

        if (empty($errors)) {
            $newStr = json_encode($errors);
            $response->getBody()->write($newStr);
            return $response
                ->withStatus(422);
        }

        $user = new User($params['lastname'], $params['email'], password_hash($params['password'], PASSWORD_DEFAULT), $params['firstname']?? null, $params['secondname']?? null, $params['phone']?? null, $params['age']?? null);
        $this->userRepository->add($user, true);

        $response->getBody()->write("Поздравляю, аккаунт создан!");
        return $response->withStatus(201);
    }
    #Проверка на валидацию
    private function validateSignUp(array $params): array
    {
        $messages = [];

        $lastnameError = $this->validateLastname($params);
        if(empty($lastnameError))
        {
            $messages['lastname']  = $lastnameError;
        }

        $emailError = $this->validateEmail($params['email']);
        if(empty($lastnameError))
        {
            $messages['email']  = $emailError;
        }

        $messages['password']  = $this->validatePassword($params['password']);

        return $messages;
    }



    private function validateLastname(array $params): ?string
    {
        $messages = null;
        if(empty($params['lastname'])){
           $messages = 'Lastname not be empty';
        }
        return $messages;
    }

    private function validatePassword(string $password): ?string
    {
        if(empty($password)){
            $messages = 'Password not be empty';
        }else
        {
            return null;
        }
        return $messages;
    }

    private function validateEmail(string $email): ?string
    {
        $messages = null;

        if(empty($email)) {
            $messages = "Email not be empty or null!";
        } else {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $messages = "The email address specified is not correct";
            }else {
                $userFindEmail = $this->userRepository->findOneByEmail($email);
                if ($userFindEmail instanceof User) {
                    $messages = "User with the same email already exists";
                }
            }
        }
        return $messages;
    }


    #Вход в систему, в свой аккаунт
    public function signIn (Request $request, Response $response)
    {
        $params = json_decode($request->getBody()->getContents(), true);

        $errorsLogin = $this->validateSignIn($params);

        if (empty($errorsLogin)) {
            $newStr = json_encode($errorsLogin);
            $response->getBody()->write($newStr);
            return $response
                ->withStatus(422);
        }

        $login = $params['login'];
        $password = $params['password'];

        $user = $this->userRepository->findOneByEmail($login);
        if($user instanceof User) {
            $result = password_verify($password, $user->getPassword());
            if($result) {
                $response->getBody()->write("Вы успешно зашли в систему");
                if(empty($user->getToken()))
                {
                    $token = Uuid::uuid4()->toString();
                    $user->setToken($token);
                    $this->userRepository->add($user, true);
                }else{
                    $token = $user->getToken();
                }

                return $response
                    ->withStatus(200)
                    ->withHeader('XToken', $token);
            }else {
                $response->getBody()->write("Password entered incorrectly");
                return $response
                    ->withStatus(422);
            }
        } else {
            $response->getBody()->write("Login entered incorrectly");

            return $response
                ->withStatus(422);
        }
    }

    private function validateSignIn(array $params): array
    {
        $messages = [];

        $messages['login']  = $this->validateLogin($params['login']);

        $messages['password']  = $this->validatePassword($params['password']);

        return $messages;
    }

    private function validateLogin(string $login): ?string
    {
        $messages = null;

        if(empty($login)) {
            $messages = "Login not be empty or null!";
        } else {
            if(!filter_var($login, FILTER_VALIDATE_EMAIL)){
                $messages = "The email address specified is not correct";
            }
        }
        return $messages;
    }

}