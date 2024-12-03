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

    private static $runeNameMap = [
        'Lethal Tempo' => 'lethalstempo',
        'Press the Attack' => 'presstheattack',
        'Fleet Footwork' => 'fleetfootwork',
        'Conqueror' => 'conqueror',
        'Electrocute' => 'electrocute',
        'Dark Harvest' => 'darkharvest',
        'Hail of Blades' => 'hailofblades',
        'Phase Rush' => 'phaserush',
        'Grasp of the Undying' => 'graspoftheundying',
        'Aftershock' => 'aftershock',
        'Guardian' => 'guardian',
        'Glacial Augment' => 'glacialaugment',
        'Unsealed Spellbook' => 'unsealedspellbook',
        'First Strike' => 'firststrike'
    ];

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

    public static function getRuneIcon($runeName)
    {
        // Use mapping if available
        if (isset(self::$runeNameMap[$runeName])) {
            $fileName = self::$runeNameMap[$runeName] . '.png';
            return asset('storage/icons/runes/' . $fileName);
        }
        
        // Debug the incoming rune name
        \Log::info("Getting rune icon for: " . $runeName);
        
        // First, try the lowercase format (this is what we want to use)
        $path = 'storage/icons/runes/' . strtolower(str_replace(' ', '', $runeName)) . '.png';
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        
        // If not found, try the format with _rune suffix
        $path = 'storage/icons/runes/' . str_replace(' ', '_', $runeName) . '_rune.png';
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        
        // If still not found, try with first letter capitalized
        $path = 'storage/icons/runes/' . ucfirst(strtolower(str_replace(' ', '_', $runeName))) . '_rune.png';
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        
        // Log if we can't find the file
        \Log::warning("Rune icon not found for: " . $runeName);
        
        // Return placeholder
        return asset('storage/icons/runes/placeholder.png');
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