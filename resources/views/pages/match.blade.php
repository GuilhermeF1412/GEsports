@extends('layouts.app')

@php
use App\Helpers\ChampionIconHelper;
use App\Helpers\GameIconHelper;

// Instantiate helper classes
$ChampionIconHelper = new ChampionIconHelper();
$GameIconHelper = new GameIconHelper();
@endphp

@section('content')
<div class="match-page">
    <div class="match-container">
        @include('components.match.header', ['match' => $match])

        <!-- Games Section -->
        @if(!empty($gameDetails))
            <div class="games-section">
                @foreach($gameDetails as $index => $game)
                    @include('components.match.game-card', [
                        'game' => $game,
                        'index' => $index,
                        'ChampionIconHelper' => $ChampionIconHelper,
                        'GameIconHelper' => $GameIconHelper
                    ])
                @endforeach
            </div>
        @endif

        <!-- Recent Matches Section -->
        <div class="recent-matches-container">
            @foreach(['1', '2'] as $teamNumber)
                @php
                    $teamName = $match["Team{$teamNumber}"];
                    $teamImage = $match["Team{$teamNumber}Image"];
                    $recentMatches = ${"team{$teamNumber}RecentMatches"} ?? [];
                @endphp

                @if($teamName !== 'TBD')
                    @include('components.match.recent-matches', [
                        'teamName' => $teamName,
                        'teamImage' => $teamImage,
                        'recentMatches' => $recentMatches,
                        'currentTeam' => $teamName
                    ])
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
