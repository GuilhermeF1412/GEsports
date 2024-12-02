<?php

namespace App\Helpers;

class ChampionIconDownloader
{
    private static $ddragonVersion = '14.1.1';
    private static $ddragonBaseUrl = 'https://ddragon.leagueoflegends.com/cdn/';

    public static function downloadAllChampions()
    {
        // Create directories if they don't exist
        $defaultDir = storage_path('app/public/champions/default');
        $bannedDir = storage_path('app/public/champions/banned');
        
        if (!file_exists($defaultDir)) mkdir($defaultDir, 0777, true);
        if (!file_exists($bannedDir)) mkdir($bannedDir, 0777, true);

        // Get champion data
        $url = self::$ddragonBaseUrl . self::$ddragonVersion . '/data/en_US/champion.json';
        $data = json_decode(file_get_contents($url), true);

        foreach ($data['data'] as $champion) {
            $championId = $champion['id'];
            
            // Download default icon
            $imageUrl = self::$ddragonBaseUrl . self::$ddragonVersion . '/img/champion/' . $champion['image']['full'];
            $defaultPath = $defaultDir . '/' . $championId . '.png';
            $bannedPath = $bannedDir . '/' . $championId . '.png';

            // Download and save default icon
            if (file_put_contents($defaultPath, file_get_contents($imageUrl))) {
                echo "Downloaded default icon for {$championId}\n";
                
                // Copy for banned version
                copy($defaultPath, $bannedPath);
                echo "Created banned icon for {$championId}\n";
            } else {
                echo "Failed to download icon for {$championId}\n";
            }
        }
    }
} 