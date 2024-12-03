<?php

namespace App\Helpers;

class RuneIconHelper
{
    public static function getRuneIcon($runeId)
    {
        // Check if the image exists
        $imagePath = 'storage/runes/' . $runeId . '.png';
        
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        
        // Return a placeholder if image doesn't exist
        return asset('storage/runes/placeholder.png');
    }
} 