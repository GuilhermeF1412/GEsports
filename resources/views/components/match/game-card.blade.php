@props(['game', 'index', 'ChampionIconHelper', 'GameIconHelper'])

@php
    $dateTime = \Carbon\Carbon::parse($game['DateTime_UTC']);
    $team1Bans = $ChampionIconHelper->formatChampionList($game['Team1Bans']);
    $team2Bans = $ChampionIconHelper->formatChampionList($game['Team2Bans']);
    $hasBans = !empty(array_filter($team1Bans, fn($ban) => $ban !== 'None')) || 
               !empty(array_filter($team2Bans, fn($ban) => $ban !== 'None'));
@endphp

<div class="game-card">
    <div class="game-header">
        <h4>Game {{ $index + 1 }}</h4>
        <span class="game-time">{{ $dateTime->format('H:i') }} UTC</span>
    </div>

    <!-- Team Stats Overview -->
    @include('components.match.game-stats', ['game' => $game])

    <!-- Bans Section -->
    @if($hasBans)
        @include('components.match.game-bans', [
            'team1Bans' => $team1Bans,
            'team2Bans' => $team2Bans,
            'ChampionIconHelper' => $ChampionIconHelper
        ])
    @endif

    <!-- Players Stats Table -->
    @include('components.match.players-table', [
        'game' => $game,
        'ChampionIconHelper' => $ChampionIconHelper,
        'GameIconHelper' => $GameIconHelper
    ])
</div> 