<?php

namespace App\Http\Controllers;

use App\Services\APIService;
use App\Helpers\ImageBuilder;
use App\Models\TeamImage;

class APIController extends Controller
{
    protected $apiService;
    protected $imageBuilder;

    public function __construct(APIService $apiService, ImageBuilder $imageBuilder)
    {
        $this->apiService = $apiService;
        $this->imageBuilder = $imageBuilder;
    }

    public function index()
    {
        $test = $this->apiService->getTeamImagesAndIDs();
        return view('pages.test', compact('test'));
    }

    public function todayMatches()
    {
        $todayMatches = $this->apiService->getTodayMatches();

        $currentTime = time(); // Current timestamp for comparison
        foreach ($todayMatches as &$match) {
            $matchStartTime = strtotime($match['DateTime UTC']);
            $bestOf = $match['BestOf'] ?? 1; // Default to 1 if BestOf is not provided

            // Determine if the match is live or finished
            if ($currentTime >= $matchStartTime) {
                if ($match['Team1Score'] >= ceil($bestOf / 2) || $match['Team2Score'] >= ceil($bestOf / 2)) {
                    $match['status'] = 'FINISHED';
                } else {
                    $match['status'] = 'IS LIVE';
                }
            } else {
                $match['status'] = ''; // Optional: No status for matches not yet live
            }

            $match['Team1Image'] = $this->getTeamImage($match['Team1OverviewPage']);
            $match['Team2Image'] = $this->getTeamImage($match['Team2OverviewPage']);

        }

        usort($todayMatches, function($a, $b) {
            return strcmp($a['Name'], $b['Name']);
        });

        return view('pages.home', compact('todayMatches'));
    }

    private function getTeamImage($teamId)
    {
        $teamImage = TeamImage::where('team_id', $teamId)->first();
        return $teamImage ? asset('storage/' . $teamImage->source) : asset('img/placeholder.png');
    }
}
