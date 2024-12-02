<?php

namespace App\Helpers;

class IconDownloader
{
    private static $ddragonVersion = '14.1.1';
    private static $ddragonBaseUrl = 'https://ddragon.leagueoflegends.com/cdn/';
    private static $cdragonBaseUrl = 'https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/assets/items/icons2d/';

    public static function downloadAllIcons()
    {
        self::downloadSummonerSpells();
        self::downloadItems();
    }

    public static function downloadSummonerSpells()
    {
        // Create directory if it doesn't exist
        $directory = storage_path('app/public/summonerspells');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Get summoner spells data
        $url = self::$ddragonBaseUrl . self::$ddragonVersion . '/data/en_US/summoner.json';
        $data = json_decode(file_get_contents($url), true);

        foreach ($data['data'] as $spell) {
            $spellId = $spell['id'];
            $imageUrl = self::$ddragonBaseUrl . self::$ddragonVersion . '/img/spell/' . $spell['image']['full'];
            $savePath = $directory . '/' . $spellId . '.png';

            file_put_contents($savePath, file_get_contents($imageUrl));
            echo "Downloaded {$spellId}.png\n";
        }
    }

    public static function downloadItems()
    {
        // Create or clean directory
        $directory = storage_path('app/public/items');
        if (file_exists($directory)) {
            array_map('unlink', glob($directory . '/*.png'));
            array_map('unlink', glob($directory . '/*.json'));
            echo "Cleaned existing items directory\n";
        } else {
            mkdir($directory, 0777, true);
            echo "Created new items directory\n";
        }

        // Get items data from Data Dragon for mappings
        $url = self::$ddragonBaseUrl . self::$ddragonVersion . '/data/en_US/item.json';
        echo "Downloading items data from: {$url}\n";
        
        $data = json_decode(file_get_contents($url), true);
        echo "Found " . count($data['data']) . " items\n";

        // Create item mappings array
        $itemMappings = [];
        $downloadedCount = 0;

        foreach ($data['data'] as $itemId => $item) {
            $itemName = $item['name'];
            echo "Processing item: {$itemName} (ID: {$itemId})\n";
            $itemMappings[$itemName] = $itemId;

            // Try Community Dragon URL
            $imageUrl = self::$cdragonBaseUrl . $itemId . '.png';
            $savePath = $directory . '/' . $itemId . '.png';

            try {
                $imageContent = @file_get_contents($imageUrl);
                if ($imageContent !== false) {
                    file_put_contents($savePath, $imageContent);
                    $downloadedCount++;
                    echo "Downloaded item {$itemId}.png ({$itemName})\n";
                } else {
                    // If Community Dragon fails, try Data Dragon
                    $ddragonUrl = self::$ddragonBaseUrl . self::$ddragonVersion . '/img/item/' . $itemId . '.png';
                    $imageContent = @file_get_contents($ddragonUrl);
                    if ($imageContent !== false) {
                        file_put_contents($savePath, $imageContent);
                        $downloadedCount++;
                        echo "Downloaded item {$itemId}.png from Data Dragon ({$itemName})\n";
                    } else {
                        echo "Failed to download item {$itemId}.png ({$itemName})\n";
                    }
                }
            } catch (\Exception $e) {
                echo "Error downloading item {$itemId}.png ({$itemName}): {$e->getMessage()}\n";
            }
        }

        // Save mappings to a JSON file
        $mappingsPath = storage_path('app/public/items/mappings.json');
        file_put_contents($mappingsPath, json_encode($itemMappings, JSON_PRETTY_PRINT));
        echo "\nDownloaded {$downloadedCount} items\n";
        echo "Saved item mappings to mappings.json\n";
    }
} 