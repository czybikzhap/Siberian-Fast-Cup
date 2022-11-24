<?php

namespace App\Controller;

use App\Entity\Subscrib;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Repository\UserRepository;
use App\Repository\SubscribRepository;

class SubscribController
{
    private ?UserRepository $userRepository;
    private ?SubscribRepository $subscribRepository;

    public function __construct(UserRepository $userRepository = null, SubscribRepository $subscribRepository= null)
    {
        $this->userRepository = $userRepository;
        $this->subscribRepository = $subscribRepository;
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

        $subscrib = new Subscrib($user, $rightuser);
        $this->subscribRepository->add($subscrib, true);

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

        $subscrib = $this->subscribRepository->findOneByFollower($user->getId(), $rightuser->getId());
        $this->subscribRepository->delete($subscrib, true);

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

        foreach ($subscribers as $subscriber)
        {
            $params[] = $subscriber->getLeftUser()->toArray();
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

        foreach ($subscriptions as $subscription)
        {
            $params[] = $subscription->getRightUser()->toArray();
        }

        $response->getBody()->write(json_encode($params));
        return $response
            ->withStatus(201);
    }
}