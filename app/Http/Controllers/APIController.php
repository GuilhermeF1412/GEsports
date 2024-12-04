<?php

namespace App\Http\Controllers;

use App\Services\APIService;
use App\Helpers\ImageBuilder;
use App\Models\TeamImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class APIController extends Controller
{
    protected $apiService;
    protected $imageBuilder;
    protected $fastApiUrl;

    public function __construct(APIService $apiService, ImageBuilder $imageBuilder)
    {
        $this->apiService = $apiService;
        $this->imageBuilder = $imageBuilder;
        $this->fastApiUrl = 'http://localhost:8001';
    }

    public function index()
    {
        $test = $this->apiService->getTeamImagesAndIDs();
        return view('pages.test', compact('test'));
    }

    public function todayMatches(Request $request)
    {
        try {
            $date = $request->input('date');
            Log::info('Fetching matches with date:', ['date' => $date]);
            
            $matches = $this->apiService->getTodayMatches($date);
            Log::info('Raw matches response:', ['matches' => $matches]);
            
            if (empty($matches)) {
                Log::warning('No matches found for date: ' . ($date ?? 'today'));
                return view('pages.home', [
                    'todayMatches' => [],
                    'selectedDate' => $date ?? now()->format('Y-m-d')
                ]);
            }

            $currentTime = time();
            foreach ($matches as &$match) {
                try {
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
                } catch (\Exception $e) {
                    Log::error('Error processing match: ' . $e->getMessage(), ['match' => $match]);
                    continue;
                }
            }

            usort($matches, function($a, $b) {
                return strcmp($a['Name'], $b['Name']);
            });

            Log::info('Processed matches:', ['count' => count($matches), 'first_match' => $matches[0] ?? null]);

            return view('pages.home', [
                'todayMatches' => $matches,
                'selectedDate' => $date ?? now()->format('Y-m-d')
            ]);
        } catch (\Exception $e) {
            Log::error('Error in todayMatches: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('lolhome')->with('error', 'Unable to fetch matches');
        }
    }

    public function showMatch($matchId, Request $request)
    {
        try {
            // Get the date from the referer URL or use today's date
            $referer = $request->header('Referer');
            $date = null;
            
            if ($referer) {
                parse_str(parse_url($referer, PHP_URL_QUERY), $query);
                $date = $query['date'] ?? null;
            }

            $matches = $this->apiService->getTodayMatches($date);
            $match = collect($matches)->first(function ($match) use ($matchId) {
                return $match['Team1'] . '-vs-' . $match['Team2'] === $matchId;
            });

            if (!$match) {
                return redirect()->route('lolhome')->with('error', 'Match not found');
            }

            // Fetch game details
            $gameDetails = $this->apiService->getMatchGames(
                $match['Team1'],
                $match['Team2'],
                $date ?? now()->format('Y-m-d')
            );
            
            \Log::info('Game Details:', [
                'Number of games' => count($gameDetails),
                'Match BestOf' => $match['BestOf'],
                'Match Score' => $match['Team1Score'] . '-' . $match['Team2Score'],
                'Games' => array_map(function($game) {
                    return [
                        'DateTime' => $game['DateTime_UTC'],
                        'Winner' => $game['Winner'],
                        'Score' => $game['Team1Score'] . '-' . $game['Team2Score']
                    ];
                }, $gameDetails)
            ]);

            // Add status
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

            Log::info('Match data:', [
                'Team1' => $match['Team1'],
                'Team1OverviewPage' => $match['Team1OverviewPage'],
                'Team2' => $match['Team2'],
                'Team2OverviewPage' => $match['Team2OverviewPage']
            ]);

            // Get team images
            $match['Team1Image'] = $this->getTeamImage($match['Team1']);
            $match['Team2Image'] = $this->getTeamImage($match['Team2']);

            Log::info('Team images:', [
                'Team1Image' => $match['Team1Image'],
                'Team2Image' => $match['Team2Image']
            ]);

            if (!empty($gameDetails)) {
                foreach ($gameDetails as &$game) {
                    // Add team images for each game
                    $game['Team1Image'] = $this->getTeamImage($game['Team1']);
                    $game['Team2Image'] = $this->getTeamImage($game['Team2']);
                }
            }

            return view('pages.match', compact('match', 'gameDetails'));
        } catch (\Exception $e) {
            Log::error('Error in showMatch: ' . $e->getMessage());
            return redirect()->route('lolhome')->with('error', 'Unable to fetch match details');
        }
    }

    private function getTeamImage($teamName)
    {
        if (!$teamName) {
            return asset('storage/teamimages/placeholder.png');
        }
        
        Log::info('Looking up team image for:', ['teamName' => $teamName]);
        
        // Try to find by team name
        $teamImage = TeamImage::where('team_id', $teamName)->first();
        if ($teamImage) {
            Log::info('Found team image:', ['source' => $teamImage->source]);
            return asset('storage/' . $teamImage->source);
        }
        
        // Check if image exists directly
        if (Storage::disk('public')->exists('teamimages/' . $teamName . '.png')) {
            Log::info('Found direct image file for team');
            return asset('storage/teamimages/' . $teamName . '.png');
        }
        
        Log::info('No image found for team, using placeholder');
        return asset('storage/teamimages/placeholder.png');
    }
}
