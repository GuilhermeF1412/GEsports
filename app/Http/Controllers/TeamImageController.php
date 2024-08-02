<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TeamImage;
use App\Services\APIService;
use App\Helpers\ImageBuilder;

class TeamImageController extends Controller
{

    protected $apiService;

    protected $imageBuilder;

    public function __construct(APIService $apiService, ImageBuilder $imageBuilder)
    {
        $this->apiService = $apiService;
        $this->imageBuilder = $imageBuilder;
    }

    public function storeTeamimage(Request $request)
    {
        $teamImagesAndIDs = $this->apiService->getTeamImagesAndIDs();

        foreach ($teamImagesAndIDs as $key => $team) {
            $teamId = $team['OverviewPage'];
            $teamImageFilename = $team['Image'];

            $imageUrl = $this->imageBuilder->getFilenameUrlToOpen($teamImageFilename);

            $imageContents = file_get_contents($imageUrl);
            $directory = 'teamimages';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            $filename = $teamId . '.png';
            $filePath = $directory . '/' . $filename;

            Storage::disk('public')->put($filePath, $imageContents);

            $teamImage = new TeamImage;
            $teamImage->team_id = $teamId;
            $teamImage->source = $filePath;
            $teamImage->save();
        }

        return response()->json(['message' => 'Team images stored successfully']);

    }
}
