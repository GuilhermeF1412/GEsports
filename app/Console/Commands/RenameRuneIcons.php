<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RenameRuneIcons extends Command
{
    protected $signature = 'icons:rename-runes';
    protected $description = 'Rename rune icons to match the naming convention';

    private $runeNameMap = [
        // Precision
        'Press_the_Attack_rune.png' => 'presstheattack.png',
        'Lethal_Tempo_rune.png' => 'lethalstempo.png',
        'Fleet_Footwork_rune.png' => 'fleetfootwork.png',
        'Conqueror_rune.png' => 'conqueror.png',
        'Precision_icon.png' => 'precision.png',
        
        // Domination
        'Electrocute_rune.png' => 'electrocute.png',
        'Predator_rune.png' => 'predator.png',
        'Dark_Harvest_rune.png' => 'darkharvest.png',
        'Hail_of_Blades_rune.png' => 'hailofblades.png',
        'Domination_icon.png' => 'domination.png',
        
        // Sorcery
        'Summon_Aery_rune.png' => 'summonaery.png',
        'Arcane_Comet_rune.png' => 'arcanecomet.png',
        'Phase_Rush_rune.png' => 'phaserush.png',
        'Sorcery_icon.png' => 'sorcery.png',
        
        // Resolve
        'Grasp_of_the_Undying_rune.png' => 'graspoftheundying.png',
        'Aftershock_rune.png' => 'aftershock.png',
        'Guardian_rune.png' => 'guardian.png',
        'Resolve_icon.png' => 'resolve.png',
        
        // Inspiration
        'Glacial_Augment_rune.png' => 'glacialaugment.png',
        'Unsealed_Spellbook_rune.png' => 'unsealedspellbook.png',
        'First_Strike_rune.png' => 'firststrike.png',
        'Inspiration_icon.png' => 'inspiration.png'
    ];

    public function handle()
    {
        $this->info('Starting rune icon renaming...');
        
        // The files are in storage/app/public/runes instead of storage/app/public/icons/runes
        foreach ($this->runeNameMap as $oldName => $newName) {
            $oldPath = 'runes/' . $oldName;  // Changed path
            $newPath = 'runes/' . $newName;  // Changed path
            
            if (Storage::disk('public')->exists($oldPath)) {
                try {
                    Storage::disk('public')->move($oldPath, $newPath);
                    $this->info("Renamed: {$oldName} -> {$newName}");
                } catch (\Exception $e) {
                    $this->error("Failed to rename {$oldName}: " . $e->getMessage());
                }
            } else {
                $this->warn("File not found: {$oldPath}");
            }
        }

        $this->info('Rune icon renaming completed!');
    }
} 