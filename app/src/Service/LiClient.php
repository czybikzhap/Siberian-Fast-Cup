<?php
namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://lichess.org/api/'
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getGames(string $lichessName): ?array
    {

        $response = $this->client->request('GET', "games/user/" . $lichessName,
        [
            'headers' => [
                'Accept' => ['application/x-ndjson']
            ],
            'query' => 'max=10'
        ]
    );

        if($response->getStatusCode() === 200){
            return json_decode($response->getBody()->getContents(), true);
        }
        throw new \Exception("LiClient status not 200!");
    }


}
