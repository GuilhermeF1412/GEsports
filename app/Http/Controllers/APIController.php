<?php

namespace App\Http\Controllers;

use App\Services\APIService;
use App\Helpers\ImageBuilder;

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

        // Add image URLs to the matches
        /*$filenames = [];
        foreach ($todayMatches as $match) {
            if (!empty($match['Team1Image'])) {
                $filenames[] = $match['Team1Image'];
            }
            if (!empty($match['Team2Image'])) {
                $filenames[] = $match['Team2Image'];
            }
        }
        $filenames = array_unique($filenames);

        // Fetch image URLs for the images
        $imageUrls = [];
        foreach ($filenames as $filename) {
            $imageUrls[$filename] = $this->imageBuilder->getFilenameUrlToOpen($filename);
        }*/

        // Add image URLs and status to the matches
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

            // Set image URLs
            /*$match['Team1ImageUrl'] = !empty($match['Team1Image']) ? $imageUrls[$match['Team1Image']] : null;
            $match['Team2ImageUrl'] = !empty($match['Team2Image']) ? $imageUrls[$match['Team2Image']] : null;*/
        }

        return view('pages.home', compact('todayMatches'));
    }
}
