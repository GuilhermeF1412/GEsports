@props(['player', 'ChampionIconHelper', 'GameIconHelper'])

<div class="champion-info">
    <div class="runes-column">
        @foreach(['keystone', 'primaryTree', 'secondaryTree'] as $runeType)
            @if($player[$runeType])
                <img src="{{ $GameIconHelper->getRuneIcon($player[$runeType]) }}" 
                     alt="{{ $player[$runeType] }}"
                     class="{{ $runeType === 'keystone' ? 'keystone-icon' : 'rune-tree-icon' }}"
                     title="{{ $player[$runeType] }}">
            @endif
        @endforeach
    </div>

    <img src="{{ $ChampionIconHelper->getChampionIcon($player['champion']) }}" 
         alt="{{ $player['champion'] }}"
         class="champion-icon">

    <div class="summoner-spells">
        @foreach($player['summonerSpells'] as $spell)
            <img src="{{ $GameIconHelper->getSummonerSpellIcon($spell) }}" 
                 alt="{{ $spell }}"
                 class="spell-icon">
        @endforeach
    </div>
</div> 