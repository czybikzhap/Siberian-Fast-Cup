<?php
namespace App\Controller;

use App\Entity\Subscrib;
use PDO;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\FollowerRepository;

class UserController
{

    private ?UserRepository $userRepository;
    private ?FollowerRepository $followerRepository;

    public function __construct(UserRepository $userRepository = null, FollowerRepository $followerRepository = null)
    {
        $this->userRepository = $userRepository;
        $this->followerRepository = $followerRepository;
    }

    #Регистрация пользователя
    public function signUp (Request $request, Response $response)
    {
        $params = json_decode($request->getBody()->getContents(), true);
        $errors = $this->validateSignUp($params);

        if (!empty($errors)) {
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
        if(!empty($lastnameError))
        {
            $messages['lastname']  = $lastnameError;
        }

        $emailError = $this->validateEmail($params);
        if(!empty($lastnameError))
        {
            $messages['email']  = $emailError;
        }

        $passwordError = $this->validatePassword($params);
        if(!empty($passwordError))
        {
            $messages['password']  = $passwordError;
        }

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

    private function validatePassword(array $params): ?string
    {
        $messages = null;
        if(empty($params['password'])){
            $messages = 'Password not be empty';
        }

        return $messages;
    }

    private function validateEmail(array $params): ?string
    {
        $messages = null;

        if(empty($params['email'])) {
            $messages = "Email not be empty or null!";
        } else {
            if(!filter_var($params['email'], FILTER_VALIDATE_EMAIL)){
                $messages = "The email address specified is not correct";
            }else {
                $userFindEmail = $this->userRepository->findOneByEmail($params['email']);
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

        if (!empty($errorsLogin)) {
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
                    ->withHeader('Token', $token);
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

        $emailError = $this->validateLogin($params);
        if(!empty($lastnameError))
        {
            $messages['login']  = $emailError;
        }

        $passwordError = $this->validatePassword($params);
        if(!empty($passwordError))
        {
            $messages['password']  = $passwordError;
        }

        return $messages;
    }

    private function validateLogin(array $params): ?string
    {
        $messages = null;

        if(empty($params['login'])) {
            $messages = "Login not be empty or null!";
        } else {
            if(!filter_var($params['login'], FILTER_VALIDATE_EMAIL)){
                $messages = "The email address specified is not correct";
            }
        }
        return $messages;
    }

    public function getInfo(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);
        $user = $this->userRepository->findOneByToken($token);

        if(!empty($user))
        {
            $userInfo = json_encode($user->toArray());
            $response->getBody()->write(($userInfo));
            return $response
                ->withStatus(200);
        }

        $response->getBody()->write("Token entered incorrectly");

        return $response
            ->withStatus(422);

    }

    public function editIInfo(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);

        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $params = json_decode($request->getBody()->getContents(), true);
        if(empty($params))
        {
            $response->getBody()->write("body empty");
            return $response
                ->withStatus(422);
        }

        if (array_key_exists ('lastname', $params))
        {
            $errors = $this->validateLastname($params);
            if (!empty($errors)) {
                $newStr = json_encode($errors);
                $response->getBody()->write($newStr);
                return $response
                    ->withStatus(422);
            }

            if ($user->getLastName() !== $params['lastname']) {
                $user->setLastName($params['lastname']);
            }
        }

        if (array_key_exists ('firstname', $params))
        {
            $errors = $this->validateFirstname($params);
            if (!empty($errors)) {
                $newStr = json_encode($errors);
                $response->getBody()->write($newStr);
                return $response
                    ->withStatus(422);
            }

            if ($user->getFirstName() !== $params['firstname']) {
                $user->setFirstName($params['firstname']);
            }
        }

        if (array_key_exists ('secondname', $params))
        {
            $errors = $this->validateSecondname($params);
            if (!empty($errors)) {
                $newStr = json_encode($errors);
                $response->getBody()->write($newStr);
                return $response
                    ->withStatus(422);
            }

            if ($user->getSecondName() !== $params['secondname']) {
                $user->setSecondName($params['secondname']);
            }
        }

        if (array_key_exists ('email', $params))
        {
            $errors = $this->validateEmail($params);
            if (!empty($errors)) {
                $newStr = json_encode($errors);
                $response->getBody()->write($newStr);
                return $response
                    ->withStatus(422);
            }

            if ($user->getEmail() !== $params['email']) {
                $user->setEmail($params['email']);
            }

        }

        if (array_key_exists ('phone', $params))
        {
            $errors = $this->validatePhone($params);
            if (!empty($errors)) {
                $newStr = json_encode($errors);
                $response->getBody()->write($newStr);
                return $response
                    ->withStatus(422);
            }

            if ($user->getPhone() !== $params['phone']) {
                $user->setPhone($params['phone']);
            }
        }

        if (array_key_exists ('age', $params))
        {
            $errors = $this->validateAge($params);
            if (!empty($errors)) {
                $newStr = json_encode($errors);
                $response->getBody()->write($newStr);
                return $response
                    ->withStatus(422);
            }

            if ($user->getAge() !== $params['age']) {
                $user->setAge($params['age']);
            }
        }

        if (array_key_exists ('password', $params))
        {
            $errors = $this->validatePassword($params);
            if (!empty($errors)) {
                $newStr = json_encode($errors);
                $response->getBody()->write($newStr);
                return $response
                    ->withStatus(422);
            }

            if ($user->getPassword() !== $params['password']) {
                $user->setPassword($params['password']);
            }
        }

        $this->userRepository->add($user, true);

        var_dump($user);
        return $response
            ->withStatus(201);

    }

    private function validateFirstname(array $params): ?string
    {
        $messages = null;
        if(empty($params['firstname'])){
            $messages = 'Firstname not be empty';
        }
        return $messages;
    }

    private function validateSecondname(array $params): ?string
    {
        $messages = null;
        if(empty($params['secondname'])){
            $messages = 'Secondname not be empty';
        }
        return $messages;
    }

    private function validatePhone(array $params): ?string
    {
        $messages = null;
        if(empty($params['phone'])){
            $messages = 'Phone not be empty';
        }
        return $messages;
    }

    private function validateAge(array $params): ?string
    {
        $messages = null;
        if(empty($params['age'])){
            $messages = 'Age not be empty';
        }
        return $messages;
    }

    //Delete user
    public function delete(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);
        $user = $this->userRepository->findOneByToken($token);

        if(!empty($user))
        {
            $this->userRepository->delete($user);

            return $response
                ->withStatus(200);
        }
        else{
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }
    }

    public function signOut(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);
        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $user->setToken(null);

        $this->userRepository->add($user, true);

        //var_dump($user);
        return $response
            ->withStatus(201);
    }

    public function addSubscription(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);

        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $params = json_decode($request->getBody()->getContents(), true);
        if (array_key_exists ('follower_id', $params))
        {
            if(empty($params['follower_id']))
            {
                $response->getBody()->write("follower_id not be empty or null");
                return $response
                    ->withStatus(422);
            }
        }else{
            $response->getBody()->write("follower_id not use");
            return $response
                ->withStatus(400);
        }

        $rightuser = $this->userRepository->find($params['follower_id']);
        if($rightuser === null)
        {
            $response->getBody()->write("User not found");

            return $response
                ->withStatus(422);
        }

        $follower = new Subscrib($user, $rightuser);
        $this->followerRepository->add($follower, true);

        $response->getBody()->write("Поздравляем друг добавлен!");
        return $response
            ->withStatus(201);
    }

    public function deleteSubscription(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);
        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $params = json_decode($request->getBody()->getContents(), true);
        if (array_key_exists ('follower_id', $params))
        {
            if(empty($params['follower_id']))
            {
                $response->getBody()->write("id_follower not be empty or null");
                return $response
                    ->withStatus(400);
            }
        }else{
            $response->getBody()->write("id_follower not use");
            return $response
                ->withStatus(400);
        }

        $rightuser = $this->userRepository->find($params['follower_id']);
        if($rightuser === null)
        {
            $response->getBody()->write("User not found");

            return $response
                ->withStatus(422);
        }

        $follower = $this->followerRepository->findOneByFollower($user->getId(), $rightuser->getId());
        $this->followerRepository->delete($follower, true);

        $response->getBody()->write("Вы отписались!");
        return $response
            ->withStatus(201);
    }

    //Подписчики
    public function showSubscribers(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);
        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $subscribers = $user->getSubscribers();

        $params = [];

        foreach ($subscribers as $key => $subscriber)
        {
            $params[$key] = $subscriber->getLeftUser()->toArray();
        }
        var_dump($params);
        $response->getBody()->write(json_encode($params));
        return $response
            ->withStatus(201);
    }

    //Подписки
    public function showSubscriptions(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);
        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }
        $params= [];
        $subscriptions = $user->getSubscriptions();
        foreach ($subscriptions as $key => $subscription)
        {
            $params[$key] = $subscription->getRightUser()->toArray();
        }

        $response->getBody()->write(json_encode($params));
        return $response
            ->withStatus(201);
    }
}