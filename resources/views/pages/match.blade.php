@extends('layouts.app')

@php
use App\Helpers\ChampionIconHelper;
@endphp

@section('content')
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Blue Side (Team1) -->
                <div class="col-md-4 text-center">
                    <div class="side-label blue-side mb-2">Blue Side</div>
                    <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team1'] }}</h3>
                    <h4 class="blue-side">{{ $match['Team1Score'] }}</h4>
                </div>

                <!-- Match Info -->
                <div class="col-md-4 text-center">
                    <div class="match-status mb-3">
                        @if($match['status'] === 'IS LIVE')
                            <span class="badge bg-danger">LIVE</span>
                        @elseif($match['status'] === 'FINISHED')
                            <span class="badge bg-secondary">FINISHED</span>
                        @else
                            <span class="badge bg-primary">UPCOMING</span>
                        @endif
                    </div>
                    <div class="match-time">
                        <p class="mb-1">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('F j, Y') }}</p>
                        <p class="mb-1">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('H:i') }} UTC</p>
                    </div>
                    <div class="match-details mt-3">
                        <p class="mb-1">Best of {{ $match['BestOf'] }}</p>
                        @if($match['Stream'])
                            <a href="{{ $match['Stream'] }}" target="_blank" class="btn btn-sm btn-primary mt-2">Watch Stream</a>
                        @endif
                    </div>
                </div>

                <!-- Red Side (Team2) -->
                <div class="col-md-4 text-center">
                    <div class="side-label red-side mb-2">Red Side</div>
                    <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team2'] }}</h3>
                    <h4 class="red-side">{{ $match['Team2Score'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Games Details -->
    @if(!empty($gameDetails))
        @foreach($gameDetails as $index => $game)
        <div class="card mb-3 match-details-container">
            <div class="card-header">
                <h5>Game {{ $index + 1 }} - {{ \Carbon\Carbon::parse($game['DateTime_UTC'])->format('H:i') }} UTC</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Blue Side Stats -->
                    <div class="col-md-6">
                        <h6 class="blue-side">
                            <i class="bi bi-circle-fill me-2"></i>
                            <img src="{{ $game['Team1Image'] }}" alt="{{ $game['Team1'] }}" class="team-icon me-2">
                            {{ $game['Team1'] }} (Blue Side)
                        </h6>
                        <ul class="list-unstyled">
                            <li>Gold: {{ number_format($game['Team1Gold']) }}</li>
                            <li>Kills: {{ $game['Team1Kills'] }}</li>
                            <li>Towers: {{ $game['Team1Towers'] }}</li>
                            <li>Dragons: {{ $game['Team1Dragons'] }}</li>
                            <li>Barons: {{ $game['Team1Barons'] }}</li>
                        </ul>
                    </div>

                    <!-- Red Side Stats -->
                    <div class="col-md-6">
                        <h6 class="red-side">
                            <i class="bi bi-circle-fill me-2"></i>
                            <img src="{{ $game['Team2Image'] }}" alt="{{ $game['Team2'] }}" class="team-icon me-2">
                            {{ $game['Team2'] }} (Red Side)
                        </h6>
                        <ul class="list-unstyled">
                            <li>Gold: {{ number_format($game['Team2Gold']) }}</li>
                            <li>Kills: {{ $game['Team2Kills'] }}</li>
                            <li>Towers: {{ $game['Team2Towers'] }}</li>
                            <li>Dragons: {{ $game['Team2Dragons'] }}</li>
                            <li>Barons: {{ $game['Team2Barons'] }}</li>
                        </ul>
                    </div>

                    <!-- Picks & Bans -->
                    <div class="col-12 mt-3">
                        <div class="row">
                            <!-- Blue Side Bans -->
                            <div class="col-md-6">
                                <h6 class="blue-side">Blue Side Bans</h6>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach(ChampionIconHelper::formatChampionList($game['Team1Bans']) as $champion)
                                        <div class="champion-icon" title="{{ $champion }}">
                                            <img src="{{ ChampionIconHelper::getChampionIcon($champion, 'banned') }}" 
                                                 alt="{{ $champion }}" 
                                                 class="img-fluid" 
                                                 style="width: 40px; height: 40px; opacity: 0.7;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Red Side Bans -->
                            <div class="col-md-6">
                                <h6 class="red-side">Red Side Bans</h6>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach(ChampionIconHelper::formatChampionList($game['Team2Bans']) as $champion)
                                        <div class="champion-icon" title="{{ $champion }}">
                                            <img src="{{ ChampionIconHelper::getChampionIcon($champion, 'banned') }}" 
                                                 alt="{{ $champion }}" 
                                                 class="img-fluid" 
                                                 style="width: 40px; height: 40px; opacity: 0.7;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Player Stats and Picks -->
                            <div class="col-12">
                                <div class="player-picks-container">
                                    @if(is_array($game['Team1Players']))
                                        @foreach($game['Team1Players'] as $index => $player)
                                            <div class="player-pick-row">
                                                <!-- Blue Side Player Stats -->
                                                <div class="player-stats blue-side text-end">
                                                    <div class="player-name">{{ $player['name'] }}</div>
                                                    <div class="player-kda">{{ $player['kills'] }}/{{ $player['deaths'] }}/{{ $player['assists'] }}</div>
                                                    <div class="player-gold">{{ number_format($player['gold']) }} gold</div>
                                                    <div class="player-cs">{{ $player['cs'] }} CS</div>
                                                </div>

                                                <!-- Champion Pick -->
                                                <div class="champion-pick">
                                                    <div class="champion-icon" title="{{ $player['champion'] }}">
                                                        <img src="{{ ChampionIconHelper::getChampionIcon($player['champion']) }}" 
                                                             alt="{{ $player['champion'] }}" 
                                                             class="img-fluid">
                                                    </div>
                                                </div>

                                                <!-- Red Side Player Stats -->
                                                <div class="player-stats red-side text-start">
                                                    @php
                                                        $redPlayer = $game['Team2Players'][$index] ?? null;
                                                    @endphp
                                                    @if($redPlayer)
                                                        <div class="player-name">{{ $redPlayer['name'] }}</div>
                                                        <div class="player-kda">{{ $redPlayer['kills'] }}/{{ $redPlayer['deaths'] }}/{{ $redPlayer['assists'] }}</div>
                                                        <div class="player-gold">{{ number_format($redPlayer['gold']) }} gold</div>
                                                        <div class="player-cs">{{ $redPlayer['cs'] }} CS</div>
                                                    @else
                                                        <div class="player-name">Unknown</div>
                                                        <div class="player-kda">0/0/0</div>
                                                        <div class="player-gold">0 gold</div>
                                                        <div class="player-cs">0 CS</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Fallback for old format -->
                                        @foreach(ChampionIconHelper::formatChampionList($game['Team1Picks']) as $index => $champion)
                                            <div class="player-pick-row">
                                                <!-- Blue Side Player Stats -->
                                                <div class="player-stats blue-side text-end">
                                                    <div class="player-name">{{ $champion }}</div>
                                                    <div class="player-kda">0/0/0</div>
                                                    <div class="player-gold">0 gold</div>
                                                    <div class="player-cs">0 CS</div>
                                                </div>

                                                <!-- Champion Pick -->
                                                <div class="champion-pick">
                                                    <div class="champion-icon" title="{{ $champion }}">
                                                        <img src="{{ ChampionIconHelper::getChampionIcon($champion) }}" 
                                                             alt="{{ $champion }}" 
                                                             class="img-fluid">
                                                    </div>
                                                </div>

                                                <!-- Red Side Player Stats -->
                                                <div class="player-stats red-side text-start">
                                                    <div class="player-name">Unknown</div>
                                                    <div class="player-kda">0/0/0</div>
                                                    <div class="player-gold">0 gold</div>
                                                    <div class="player-cs">0 CS</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">No game details available yet.</div>
    @endif
</div>
@endsection
