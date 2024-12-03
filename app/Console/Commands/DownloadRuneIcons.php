<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DownloadRuneIcons extends Command
{
    protected $signature = 'icons:download-runes';
    protected $description = 'Download rune icons from Data Dragon';

    private $runeData = [
        // Precision
        'Press the Attack' => '8005',
        'Lethal Tempo' => '8008',
        'Fleet Footwork' => '8021',
        'Conqueror' => '8010',
        'Precision' => '8000',
        
        // Domination
        'Electrocute' => '8112',
        'Predator' => '8124',
        'Dark Harvest' => '8128',
        'Hail of Blades' => '9923',
        'Domination' => '8100',
        
        // Sorcery
        'Summon Aery' => '8214',
        'Arcane Comet' => '8229',
        'Phase Rush' => '8230',
        'Sorcery' => '8200',
        
        // Resolve
        'Grasp of the Undying' => '8437',
        'Aftershock' => '8439',
        'Guardian' => '8465',
        'Resolve' => '8400',
        
        // Inspiration
        'Glacial Augment' => '8351',
        'Unsealed Spellbook' => '8360',
        'First Strike' => '8369',
        'Inspiration' => '8300'
    ];

    public function handle()
    {
        // First, let's check if the directory exists and try to create it
        $directory = Storage::disk('public')->path('icons/runes');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Get the latest version
        $versions = Http::withOptions(['verify' => false])
            ->get('https://ddragon.leagueoflegends.com/api/versions.json');
        
        if (!$versions->successful()) {
            $this->error('Failed to fetch version information');
            return;
        }

        $latestVersion = $versions->json()[0];
        $baseUrl = "https://ddragon.leagueoflegends.com/cdn/{$latestVersion}/img/perk/";
        
        $this->info('Using version: ' . $latestVersion);
        $this->info('Base URL: ' . $baseUrl);
        $bar = $this->output->createProgressBar(count($this->runeData));
        $this->info('Downloading rune icons...');
        
        foreach ($this->runeData as $runeName => $runeId) {
            $url = $baseUrl . $runeId . '.png';
            $this->info("\nTrying to download from: " . $url);

            try {
                $response = Http::withOptions([
                    'verify' => false
                ])->get($url);
                
                if ($response->successful()) {
                    $fileName = strtolower(str_replace(' ', '', $runeName)) . '.png';
                    $filePath = 'icons/runes/' . $fileName;
                    
                    Storage::disk('public')->put($filePath, $response->body());
                    
                    if (Storage::disk('public')->exists($filePath)) {
                        $this->info("Successfully saved to: " . Storage::disk('public')->path($filePath));
                    } else {
                        $this->error("File was not saved properly");
                    }
                    
                    $bar->advance();
                } else {
                    $this->error("Failed to download {$runeName} (Status: " . $response->status() . ")");
                    $this->error("Response: " . $response->body());
                }
            } catch (\Exception $e) {
                $this->error("Error downloading {$runeName}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->info("\nRune icons download process completed!");
    }
} 