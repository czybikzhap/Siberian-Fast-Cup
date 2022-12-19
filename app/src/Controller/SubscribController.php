<?php

namespace App\Controller;

use App\Entity\Subscribe;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Repository\UserRepository;
use App\Repository\SubscribeRepository;

class SubscribController extends AutorizationController
{
    private ?UserRepository $userRepository;
    private ?SubscribeRepository $subscribeRepository;

    public function __construct(
        UserRepository      $userRepository          = null,
        SubscribeRepository $subscribeRepository     = null)
    {
        $this->userRepository           = $userRepository;
        $this->subscribeRepository      = $subscribeRepository;
    }

    public function addSubscription(Request $request, Response $response)
    {
        $user = $this->authorization($request, $this->userRepository);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $rightUser = $this->hasReceiver($request, $this->userRepository);
        if($rightUser === null)
        {
            $response->getBody()->write("follower_id not use or not empty, user not found");
            return $response
                ->withStatus(422);
        }

        $subscribe = new Subscribe($user, $rightUser);
        $this->subscribeRepository->add($subscribe, true);

        $response->getBody()->write("Поздравляем, друг добавлен!");
        return $response
            ->withStatus(201);
    }

    public function deleteSubscription(Request $request, Response $response)
    {
        $user = $this->authorization($request, $this->userRepository);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $rightUser = $this->hasReceiver($request, $this->userRepository);
        if($rightUser === null)
        {
            $response->getBody()->write("follower_id not use or not empty, user not found");
            return $response
                ->withStatus(422);
        }

        $subscribe = $this->subscribeRepository->findOneByFollower($user->getId(), $rightUser->getId());
        $this->subscribeRepository->delete($subscribe, true);

        $response->getBody()->write("Вы отписались!");
        return $response
            ->withStatus(201);
    }

    //Подписчики
    public function showSubscribers(Request $request, Response $response)
    {
        $user = $this->authorization($request, $this->userRepository);
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

        $response->getBody()->write(json_encode($params));
        return $response
            ->withStatus(201);
    }

    //Подписки
    public function showSubscriptions(Request $request, Response $response)
    {
        $user = $this->authorization($request, $this->userRepository);
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