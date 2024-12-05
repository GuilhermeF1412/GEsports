<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TeamImageController;
use App\Services\APIService;
use App\Helpers\ImageBuilder;

class HomeController extends Controller
{
    protected $teamImageController;

    public function __construct()
    {
        $this->teamImageController = new TeamImageController(new APIService, new ImageBuilder);
    }

    public function index(Request $request)
    {
        $selectedDate = $request->date ?? now()->format('Y-m-d');
        $apiService = new APIService();
        $todayMatches = $apiService->getTodayMatches($selectedDate);

        // Group matches by tournament first
        $groupedMatches = [];
        if ($todayMatches) {
            foreach ($todayMatches as $match) {
                $groupedMatches[$match['Name']][] = $match;
            }
        }

        // Sort tournaments alphabetically
        ksort($groupedMatches);

        // Process grouped matches
        $processedMatches = [];
        $tournamentIndex = 0;

        foreach ($groupedMatches as $tournamentName => $matches) {
            $tournamentIndex++;
            
            foreach ($matches as $match) {
                // Calculate match status
                $currentTime = time();
                $matchStartTime = strtotime($match['DateTime_UTC']);
                $bestOf = $match['BestOf'] ?? 1;
                
                if ($currentTime >= $matchStartTime) {
                    if ($match['Team1Score'] >= ceil($bestOf / 2) || $match['Team2Score'] >= ceil($bestOf / 2)) {
                        $match['status'] = 'FINISHED';
                    } else {
                        $match['status'] = 'IS LIVE';
                    }
                } else {
                    $match['status'] = '';
                }

                // Process match data
                $processedMatch = $match;
                $processedMatch['tournamentIndex'] = $tournamentIndex;
                $processedMatch['team1Image'] = $this->teamImageController->getTeamImagePath($match['Team1OverviewPage']);
                $processedMatch['team2Image'] = $this->teamImageController->getTeamImagePath($match['Team2OverviewPage']);
                $processedMatch['matchUrl'] = route('match.show', [
                    'matchId' => $match['Team1'] . '-vs-' . $match['Team2'],
                    'date' => $selectedDate
                ]);
                $processedMatch['formattedTime'] = date('H:i', strtotime($match['DateTime_UTC']));
                $processedMatch['isLive'] = $match['status'] === 'IS LIVE';
                $processedMatch['team1Score'] = $match['Team1Score'] ?? '-';
                $processedMatch['team2Score'] = $match['Team2Score'] ?? '-';

                $processedMatches[] = $processedMatch;
            }
        }

        return view('pages.home', [
            'selectedDate' => $selectedDate,
            'matches' => $processedMatches,
            'formattedDate' => \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y')
        ]);
    }
}
