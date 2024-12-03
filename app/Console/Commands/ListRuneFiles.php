<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ListRuneFiles extends Command
{
    protected $signature = 'icons:list-runes';
    protected $description = 'List all rune icon files';

    public function handle()
    {
        $this->info('Listing rune icons...');
        
        $files = Storage::disk('public')->files('icons/runes');
        
        foreach ($files as $file) {
            $this->info(basename($file));
        }

        $this->info('Total files: ' . count($files));
    }
} 