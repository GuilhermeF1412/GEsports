<?php

namespace App\Helpers;

class ChampionIconHelper
{
    public static function getChampionIcon($championName, $type = 'default')
    {
        // Clean the champion name (remove special characters and spaces)
        $championName = str_replace([' ', "'", '.'], '', $championName);
        
        // Special cases for champion names
        $specialCases = [
            'KSante' => 'KSante',
            'RenataGlasc' => 'Renata',
            'Nunu&Willump' => 'Nunu',
            'Kaisa' => 'KaiSa',
            'Khazix' => 'KhaZix',
            'Reksai' => 'RekSai',
            'Velkoz' => 'VelKoz',
            // Add more special cases as needed
        ];

        $championName = $specialCases[$championName] ?? $championName;
        
        // Determine the path based on type
        $path = $type === 'banned' ? 'champions/banned/' : 'champions/default/';
        
        // Check if the image exists
        $imagePath = 'storage/' . $path . $championName . '.png';
        
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