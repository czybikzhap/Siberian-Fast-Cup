<?php

namespace App\Controller;


use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Service\LiClient;
use GuzzleHttp\Exception\GuzzleException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GameController
{

    private ?UserRepository $userRepository;
    private ?GameRepository $gameRepository;

    public function __construct(UserRepository $userRepository = null, GameRepository $gameRepository = null)
    {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
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

        $client = new LiClient();
        $response = $client->getGames($user->getLichessName());

        $params = json_decode($response->getBody()->getContents(), true);
        //print_r($params['speed']);

        $game = new Game(
            $user,
            $params['players']['white']['user']['name'],
            $params['players']['black']['user']['name'],
            $params['winner'],
            $params['players']['white']['rating'],
            $params['players']['black']['rating'],
            $params['speed'],
            $params['moves']
        );

        var_dump($game);
        $this->gameRepository->add($game, true);

        $response->getBody()->write("Поздравляю, партии сохранены!");
        return $response
            ->withStatus(201);
    }
}

