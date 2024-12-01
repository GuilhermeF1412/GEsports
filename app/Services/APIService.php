<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class APIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://127.0.0.1:8001',
            'timeout' => 5, // 5 seconds timeout
        ]);
    }

    public function getData()
    {
        try {
            $response = $this->client->get('/data');
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return [];
        }
    }

    public function getTodayMatches($date = null)
    {
        try {
            Log::info('Raw date parameter received in APIService:', ['date' => $date]);
            $url = '/TodayMatches' . ($date ? "?date={$date}" : '');
            Log::info('Making request to URL:', ['url' => $url]);
            $response = $this->client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);
            Log::info('Successfully fetched matches', ['count' => count($data), 'first_match' => $data[0] ?? null]);
            return $data;
        } catch (GuzzleException $e) {
            Log::error('Error fetching matches: ' . $e->getMessage());
            return [];
        }
    }

    public function getTeamImagesAndIDs()
    {
        try {
            $response = $this->client->get('/AllTeamImages');
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Error fetching team images: ' . $e->getMessage());
            return [];
        }
    }
}
