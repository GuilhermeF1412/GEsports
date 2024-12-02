<?php

namespace App\Helpers;

class ChampionIconHelper
{
    public static function getChampionIcon($championName, $type = 'default')
    {
        // Special cases for champion names - verified with Data Dragon
        $specialCases = [
            'Wukong' => 'MonkeyKing',
            'Xin Zhao' => 'XinZhao',
            'Jarvan IV' => 'JarvanIV',
            "Kai'Sa" => 'Kaisa',
            "K'Sante" => 'KSante',
            'Renata Glasc' => 'Renata',
            'Miss Fortune' => 'MissFortune',
            "Kog'Maw" => 'KogMaw',
            "Kha'Zix" => 'Khazix',
            "Cho'Gath" => 'Chogath',
            "Vel'Koz" => 'Velkoz',
            "Rek'Sai" => 'RekSai',
            "Bel'Veth" => 'Belveth',
            'Lee Sin' => 'LeeSin',
            'Aurelion Sol' => 'AurelionSol',
            'Dr. Mundo' => 'DrMundo',
            'Master Yi' => 'MasterYi',
            'Nunu & Willump' => 'Nunu',
            'Twisted Fate' => 'TwistedFate'
        ];

        // Convert champion name if it's a special case
        $championId = $specialCases[$championName] ?? $championName;
        
        // Determine the path based on type
        $path = $type === 'banned' ? 'champions/banned/' : 'champions/default/';
        
        // Check if the image exists
        $imagePath = 'storage/' . $path . $championId . '.png';
        
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        
        // Return a placeholder if image doesn't exist
        return asset('storage/champions/placeholder.png');
    }

    public static function formatChampionList($championString)
    {
        if (empty($championString)) {
            return [];
        }
        return array_map('trim', explode(',', $championString));
    }
} 