<?php

namespace App\Controller;


use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Service\LiClient;
use Doctrine\DBAL\Exception;
use GuzzleHttp\Exception\GuzzleException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GameController
{
    //to do
    private UserRepository $userRepository;
    private GameRepository $gameRepository;
    private LiClient $liClient;

    public function __construct(UserRepository $userRepository, GameRepository $gameRepository, LiClient $liClient)
    {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
        $this->liClient = $liClient;
    }

    /**
     * @throws GuzzleException
     */
    public function showGame(Request $request, Response $response)
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

        try {
            $games = $this->liClient->getGames($user->getLichessName());
        }catch (\Throwable $throwable)
        {
            //TODO Логирование сделать
            $response->getBody()->write("Ошибка сервера");
            return $response->withStatus(500);
        }

        $game = new Game(
            $user,
            $games['players']['white']['user']['name'],
            $games['players']['black']['user']['name'],
            $games['winner'],
            $games['players']['white']['rating'],
            $games['players']['black']['rating'],
            $games['speed'],
            $games['moves']
        );

        $this->gameRepository->add($game, true);


        $response->getBody()->write("Поздравляю, партии сохранены!");
        return $response
            ->withStatus(201);
    }
}

