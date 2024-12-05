@props(['team1Bans', 'team2Bans', 'ChampionIconHelper'])

<div class="bans-section">
    @foreach(['blue' => $team1Bans, 'red' => $team2Bans] as $side => $bans)
        <div class="team-bans {{ $side }}-side">
            @foreach($bans as $champion)
                @if($champion !== 'None')
                    <div class="ban-icon" title="{{ $champion }}">
                        <img src="{{ $ChampionIconHelper->getChampionIcon($champion) }}" 
                             alt="{{ $champion }}">
                    </div>
                @endif
            @endforeach
        </div>
        @if($side === 'blue')
            <div class="bans-label">Bans</div>
        @endif
    @endforeach
</div> 