<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\APIService;
use App\Helpers\ImageBuilder;

class DownloadTeamImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teams:download-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download all team images';

    protected $apiService;
    protected $imageBuilder;

    public function __construct(APIService $apiService, ImageBuilder $imageBuilder)
    {
        parent::__construct();
        $this->apiService = $apiService;
        $this->imageBuilder = $imageBuilder;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting team image download...');

        // Get all teams from the API
        $teams = $this->apiService->getAllTeams();

        if (empty($teams)) {
            $this->error('No teams found!');
            return 1;
        }

        $bar = $this->output->createProgressBar(count($teams));
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($teams as $team) {
            $teamId = $team['id'];
            $imageUrl = $this->imageBuilder->getFilenameUrlToOpen($teamId . '.png');
            
            if ($imageUrl && $this->imageBuilder->downloadAndSaveImage($imageUrl, $teamId)) {
                $success++;
            } else {
                $failed++;
                $this->warn("\nFailed to download image for team: " . $team['name']);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nTeam images download completed!");
        $this->info("Successfully downloaded: $success");
        $this->info("Failed downloads: $failed");

        return 0;
    }
}
