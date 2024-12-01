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

    public function getTeamImagePath($teamId)
    {
        if (empty($teamId)) {
            return asset('storage/teamimages/placeholder.png');
        }
        
        $teamImage = TeamImage::where('team_id', $teamId)->first();
        
        if ($teamImage && Storage::disk('public')->exists('teamimages/' . $teamId . '.png')) {
            $filename = rawurlencode($teamId . '.png');
            return asset('storage/teamimages/' . $filename);
        }
        
        if (Storage::disk('public')->exists('teamimages/' . $teamId . '.png')) {
            $filename = rawurlencode($teamId . '.png');
            return asset('storage/teamimages/' . $filename);
        }

        return asset('storage/teamimages/placeholder.png');
    }

    public function storeTeamimage(Request $request)
    {
        set_time_limit(0);
        
        $teamImages = TeamImage::all();
        $processed = 0;
        $failed = 0;
        $skipped = 0;

        $directory = 'teamimages';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        foreach ($teamImages as $teamImage) {
            // Skip if both database record and file exist
            if (Storage::disk('public')->exists('teamimages/' . $teamImage->team_id . '.png')) {
                $skipped++;
                continue;
            }

            try {
                $imageUrl = $this->imageBuilder->getFilenameUrlToOpen($teamImage->team_id . '.png');
                if (!$imageUrl) {
                    $failed++;
                    continue;
                }

                $context = stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]);

                $imageContents = file_get_contents($imageUrl, false, $context);
                if ($imageContents === false) {
                    $failed++;
                    continue;
                }

                $filename = $teamImage->team_id . '.png';
                $filePath = $directory . '/' . $filename;

                if (Storage::disk('public')->put($filePath, $imageContents)) {
                    $processed++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
                continue;
            }
        }

        return response()->json([
            'message' => 'Team images processed',
            'processed' => $processed,
            'failed' => $failed,
            'skipped' => $skipped,
            'total' => count($teamImages)
        ]);
    }
}
