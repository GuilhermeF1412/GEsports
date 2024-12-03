<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveRuneIcons extends Command
{
    protected $signature = 'icons:move-runes';
    protected $description = 'Move rune icons to the correct directory';

    public function handle()
    {
        $this->info('Moving rune icons...');
        
        // Create the destination directory if it doesn't exist
        if (!Storage::disk('public')->exists('icons/runes')) {
            Storage::disk('public')->makeDirectory('icons/runes');
        }

        // Get all files from the current runes directory
        $files = Storage::disk('public')->files('runes');
        
        foreach ($files as $file) {
            $filename = basename($file);
            $newPath = 'icons/runes/' . $filename;
            
            try {
                if (Storage::disk('public')->exists($file)) {
                    // Copy the file to the new location
                    Storage::disk('public')->copy($file, $newPath);
                    $this->info("Moved: {$filename} to icons/runes/");
                }
            } catch (\Exception $e) {
                $this->error("Failed to move {$filename}: " . $e->getMessage());
            }
        }

        $this->info('Rune icons move completed!');
    }
} 