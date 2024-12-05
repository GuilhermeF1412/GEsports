<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\APIService;
use App\Http\Controllers\TeamImageController;
use App\Helpers\ImageBuilder;

class MatchController extends Controller
{
    protected $apiService;
    protected $teamImageController;

    public function __construct()
    {
        $this->apiService = new APIService();
        $this->teamImageController = new TeamImageController(new APIService, new ImageBuilder);
    }

    public function show($matchId, Request $request)
    {
        try {
            $date = $request->date ?? now()->format('Y-m-d');
            
            \Log::info('Match show method called:', [
                'matchId' => $matchId,
                'date' => $date,
                'full_url' => $request->fullUrl()
            ]);

            $matches = $this->apiService->getTodayMatches($date);
            
            // Find the specific match
            $match = collect($matches)->first(function ($match) use ($matchId) {
                $expectedId = $match['Team1'] . '-vs-' . $match['Team2'];
                return $expectedId === $matchId;
            });

            if (!$match) {
                \Log::error('Match not found', ['matchId' => $matchId, 'date' => $date]);
                return redirect()->route('lolhome')->with('error', 'Match not found');
            }

            // Process match data
            $processedMatch = $match;
            $processedMatch['team1Image'] = $this->teamImageController->getTeamImagePath($match['Team1OverviewPage']);
            $processedMatch['team2Image'] = $this->teamImageController->getTeamImagePath($match['Team2OverviewPage']);
            $processedMatch['formattedTime'] = date('H:i', strtotime($match['DateTime_UTC']));
            $processedMatch['isLive'] = false; // You might want to calculate this based on your criteria
            $processedMatch['team1Score'] = $match['Team1Score'] ?? '-';
            $processedMatch['team2Score'] = $match['Team2Score'] ?? '-';
            $processedMatch['Winner'] = $match['Winner'] ?? '0';

            // Get game details
            $gameDetails = $this->apiService->getMatchGames(
                $match['Team1'],
                $match['Team2'],
                $date
            );

            // Process game details
            $processedGames = [];
            foreach ($gameDetails as $game) {
                $processedGame = $game;
                $processedGame['team1Image'] = $this->teamImageController->getTeamImagePath($game['Team1OverviewPage'] ?? $match['Team1OverviewPage']);
                $processedGame['team2Image'] = $this->teamImageController->getTeamImagePath($game['Team2OverviewPage'] ?? $match['Team2OverviewPage']);
                $processedGame['formattedTime'] = date('H:i', strtotime($game['DateTime UTC']));
                
                // Process picks and bans
                $processedGame['team1Picks'] = explode(',', $game['Team1Picks']);
                $processedGame['team2Picks'] = explode(',', $game['Team2Picks']);
                $processedGame['team1Bans'] = explode(',', $game['Team1Bans']);
                $processedGame['team2Bans'] = explode(',', $game['Team2Bans']);
                
                $processedGames[] = $processedGame;
            }

            return view('pages.match', [
                'match' => $processedMatch,
                'games' => $processedGames,
                'date' => $date
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in match show:', ['error' => $e->getMessage()]);
            return redirect()->route('lolhome')->with('error', 'Error loading match details');
        }
    }
} 