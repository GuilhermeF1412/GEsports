<?php

namespace App\Services;

use GuzzleHttp\Client;

class APIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://127.0.0.1:8000',
        ]);
    }

    public function getData()
    {
        $response = $this->client->get('/data');
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getTodayMatches()
    {
        $response = $this->client->get('/TodayMatches');
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getTeamImagesAndIDs()
    {
        $response = $this->client->get('/AllTeamImages');
        return json_decode($response->getBody()->getContents(), true);
    }
}
