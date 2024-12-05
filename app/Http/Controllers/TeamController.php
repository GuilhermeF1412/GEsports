<?php

namespace App\Http\Controllers;

use App\Services\APIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    protected $apiService;

    public function __construct(APIService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function show($teamName)
    {
        try {
            // Get team details
            $team = $this->apiService->getTeamDetails($teamName);
            
            // If team exists, get matches
            $recentMatches = [];
            $futureMatches = [];
            if ($team) {
                $recentMatches = array_slice($this->apiService->getTeamMatches($teamName), 0, 20);
                $futureMatches = $this->apiService->getTeamFutureMatches($teamName);
            }

            return view('pages.team', compact('team', 'recentMatches', 'futureMatches'));
        } catch (\Exception $e) {
            return redirect()->route('lolhome')->with('error', 'Unable to fetch team details: ' . $e->getMessage());
        }
    }
} 