@php
    $stats = [
        'blue' => [
            'gold' => ['value' => $game['Team1Gold'], 'icon' => 'gold.png'],
            'kills' => ['value' => $game['Team1Kills'], 'icon' => 'blue_kills.png'],
            'towers' => ['value' => $game['Team1Towers'], 'icon' => 'blue_tower.png'],
            'dragons' => ['value' => $game['Team1Dragons'], 'icon' => 'blue_dragon.png'],
            'barons' => ['value' => $game['Team1Barons'], 'icon' => 'blue_baron.png']
        ],
        'red' => [
            'gold' => ['value' => $game['Team2Gold'], 'icon' => 'gold.png'],
            'kills' => ['value' => $game['Team2Kills'], 'icon' => 'red_kills.png'],
            'towers' => ['value' => $game['Team2Towers'], 'icon' => 'red_tower.png'],
            'dragons' => ['value' => $game['Team2Dragons'], 'icon' => 'red_dragon.png'],
            'barons' => ['value' => $game['Team2Barons'], 'icon' => 'red_baron.png']
        ]
    ];
@endphp

<div class="game-overview">
    @foreach(['blue', 'red'] as $side)
        <div class="team-stats {{ $side }}-side">
            @foreach($stats[$side] as $stat => $data)
                <div class="stat-row">
                    <span class="stat-value">
                        <img src="/storage/icons/ui/{{ $data['icon'] }}" 
                             alt="{{ ucfirst($stat) }}" 
                             class="stat-icon">
                        {{ $stat === 'gold' ? number_format($data['value']) : $data['value'] }}
                    </span>
                    <span class="stat-label">{{ ucfirst($stat) }}</span>
                </div>
            @endforeach
        </div>

        @if($side === 'blue')
            <div class="game-result">
                <div class="winner-label">{{ $game['WinTeam'] }} Victory</div>
                <div class="game-length">{{ $game['Gamelength'] }}</div>
            </div>
        @endif
    @endforeach
</div> 