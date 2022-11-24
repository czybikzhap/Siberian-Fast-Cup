<?php
namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class LiClient
{
    /**
     * @throws GuzzleException
     */
    public function getGames(?string $lichess_name): ?ResponseInterface
    {
        $client = new Client([
            'base_uri' => 'https://lichess.org/api/games/user/' . "$lichess_name"
        ]);

        $games = $client->request('GET', '',
            [
                'headers' => [
                    'Accept' => ['application/x-ndjson']
                ],
                'query' => 'max=1'
            ]
        );

        return $games;
    }
}
