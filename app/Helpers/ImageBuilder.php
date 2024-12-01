<?php

namespace App\Helpers;

ini_set('curl.cainfo', '');

use GuzzleHttp\Client;

class ImageBuilder
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://lol.gamepedia.com/api.php',  // Keep original gamepedia URL for images
            'verify' => false  // Disable SSL verification
        ]);
    }

    public function getFilenameUrlToOpen($filename)
    {
        $response = $this->client->get('', [  // Request to the base URI with query parameters
            'query' => [
                'action' => 'query',
                'format' => 'json',
                'titles' => "File:" . $filename,
                'prop' => 'imageinfo',
                'iiprop' => 'url',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        $pages = $data['query']['pages'] ?? [];
        if (empty($pages)) {
            return null;
        }

        $page = reset($pages);
        if (isset($page['missing']) || !isset($page['imageinfo'][0])) {
            return null;
        }

        $imageInfo = $page['imageinfo'][0];
        $imageUrl = $imageInfo['url'];

        return explode('/revision', $imageUrl)[0];  // Return the URL without the revision part
    }
}
