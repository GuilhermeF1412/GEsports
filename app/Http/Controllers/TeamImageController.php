<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class TeamImageController extends Controller
{
    public function getTeamImagePath($teamId)
    {
        if (empty($teamId)) {
            return asset('storage/teamimages/placeholder.png');
        }
        
        if (Storage::disk('public')->exists('teamimages/' . $teamId . '.png')) {
            $filename = rawurlencode($teamId . '.png');
            return asset('storage/teamimages/' . $filename);
        }

        return asset('storage/teamimages/placeholder.png');
    }
}
