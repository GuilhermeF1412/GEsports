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

        foreach ($teamImagesAndIDs as $team) {
            $teamId = $team['OverviewPage'];
            $teamImageFilename = $team['Image'];

            $imageUrl = $this->imageBuilder->getFilenameUrlToOpen($teamImageFilename);

            $imageContents = file_get_contents($imageUrl);
            $directory = 'teamimages';
            $filename = $teamId . '.png';
            $filePath = $directory . '/' . $filename;

            Storage::disk('public')->put($filePath, $imageContents);

            TeamImage::create([
                'team_id' => $teamId,
                'source' => $filePath
            ]);
        }

        return response()->json(['message' => 'Team images stored successfully']);

    }
}
