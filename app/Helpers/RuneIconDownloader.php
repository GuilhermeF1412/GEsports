<?php

namespace App\Helpers;

class RuneIconDownloader
{
    private static $ddragonVersion = '14.1.1';
    private static $ddragonBaseUrl = 'https://ddragon.leagueoflegends.com/cdn/';

    public static function downloadAllRunes()
    {
        // Create directory if it doesn't exist
        $runesDir = storage_path('app/public/runes');
        if (!file_exists($runesDir)) mkdir($runesDir, 0777, true);

        // Get runes data
        $url = self::$ddragonBaseUrl . self::$ddragonVersion . '/data/en_US/runesReforged.json';
        $data = json_decode(file_get_contents($url), true);

        foreach ($data as $runeTree) {
            // Download keystone runes
            foreach ($runeTree['slots'][0]['runes'] as $keystone) {
                $iconUrl = 'https://ddragon.leagueoflegends.com/cdn/img/' . $keystone['icon'];
                $savePath = $runesDir . '/' . $keystone['id'] . '.png';
                
                if (file_put_contents($savePath, file_get_contents($iconUrl))) {
                    echo "Downloaded rune icon: {$keystone['key']}\n";
                } else {
                    echo "Failed to download rune icon: {$keystone['key']}\n";
                }
            }
        }
    }
} 