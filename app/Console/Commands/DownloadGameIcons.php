<?php

namespace App\Console\Commands;

use App\Helpers\IconDownloader;
use Illuminate\Console\Command;

class DownloadGameIcons extends Command
{
    protected $signature = 'icons:download';
    protected $description = 'Download summoner spells and items icons';

    public function handle()
    {
        $this->info('Starting download of game icons...');
        
        try {
            IconDownloader::downloadAllIcons();
            $this->info('Successfully downloaded all icons!');
        } catch (\Exception $e) {
            $this->error('Error downloading icons: ' . $e->getMessage());
        }
    }
} 