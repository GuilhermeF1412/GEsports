<?php

namespace App\Console\Commands;

use App\Helpers\ChampionIconDownloader;
use Illuminate\Console\Command;

class DownloadChampionIcons extends Command
{
    protected $signature = 'champions:download';
    protected $description = 'Download champion icons';

    public function handle()
    {
        $this->info('Starting download of champion icons...');
        
        try {
            ChampionIconDownloader::downloadAllChampions();
            $this->info('Successfully downloaded all champion icons!');
        } catch (\Exception $e) {
            $this->error('Error downloading icons: ' . $e->getMessage());
        }
    }
} 