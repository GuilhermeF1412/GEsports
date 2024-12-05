@php
use App\Helpers\ChampionIconHelper;
use App\Helpers\GameIconHelper;
@endphp

@props(['player'])

<div class="champion-info">
    <div class="runes-column">
        @if($player['keystone'])
            <img src="{{ GameIconHelper::getRuneIcon($player['keystone']) }}" 
                 alt="{{ $player['keystone'] }}"
                 class="keystone-icon"
                 title="{{ $player['keystone'] }}">
        @endif
        @if($player['primaryTree'])
            <img src="{{ GameIconHelper::getRuneIcon($player['primaryTree']) }}" 
                 alt="{{ $player['primaryTree'] }}"
                 class="rune-tree-icon"
                 title="{{ $player['primaryTree'] }}">
        @endif
        @if($player['secondaryTree'])
            <img src="{{ GameIconHelper::getRuneIcon($player['secondaryTree']) }}" 
                 alt="{{ $player['secondaryTree'] }}"
                 class="rune-tree-icon"
                 title="{{ $player['secondaryTree'] }}">
        @endif
    </div>
    <img src="{{ ChampionIconHelper::getChampionIcon($player['champion']) }}" 
         alt="{{ $player['champion'] }}"
         class="champion-icon">
    <div class="summoner-spells">
        @foreach($player['summonerSpells'] as $spell)
            <img src="{{ GameIconHelper::getSummonerSpellIcon($spell) }}" 
                 alt="{{ $spell }}"
                 class="spell-icon">
        @endforeach
    </div>
</div> 