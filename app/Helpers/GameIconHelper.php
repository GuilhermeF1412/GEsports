<?php

namespace App\Helpers;

class GameIconHelper
{
    private static $summonerSpellMap = [
        'Barrier' => 'SummonerBarrier',
        'Cleanse' => 'SummonerBoost',
        'Exhaust' => 'SummonerExhaust',
        'Flash' => 'SummonerFlash',
        'Ghost' => 'SummonerHaste',
        'Heal' => 'SummonerHeal',
        'Ignite' => 'SummonerDot',
        'Smite' => 'SummonerSmite',
        'Teleport' => 'SummonerTeleport'
    ];

    private static $itemMappings = null;

    public static function getSummonerSpellIcon($spellName)
    {
        $mappedName = self::$summonerSpellMap[$spellName] ?? $spellName;
        return "/storage/summonerspells/{$mappedName}.png";
    }

    public static function getItemIcon($itemName)
    {
        // Load mappings if not loaded
        if (self::$itemMappings === null) {
            $mappingsPath = storage_path('app/public/items/mappings.json');
            if (file_exists($mappingsPath)) {
                self::$itemMappings = json_decode(file_get_contents($mappingsPath), true);
            } else {
                self::$itemMappings = [];
            }
        }

        // Case-insensitive search
        foreach (self::$itemMappings as $name => $id) {
            if (strcasecmp($name, $itemName) === 0) {
                return "/storage/items/{$id}.png";
            }
        }

        // If not found, use placeholder
        if ($itemName !== '') {
            \Log::info("Missing item mapping for: " . $itemName);
        }
        return "/storage/items/7050.png";
    }

    // Helper function to get all available item mappings
    public static function getAllItemMappings()
    {
        if (self::$itemMappings === null) {
            $mappingsPath = storage_path('app/public/items/mappings.json');
            if (file_exists($mappingsPath)) {
                self::$itemMappings = json_decode(file_get_contents($mappingsPath), true);
            } else {
                self::$itemMappings = [];
            }
        }
        return self::$itemMappings;
    }
} 