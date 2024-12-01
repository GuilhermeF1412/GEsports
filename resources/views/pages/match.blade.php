@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Team 1 -->
                <div class="col-md-4 text-center">
                    <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team1'] }}</h3>
                    <h4 class="text-primary">{{ $match['Team1Score'] }}</h4>
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

                <!-- Team 2 -->
                <div class="col-md-4 text-center">
                    <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team2'] }}</h3>
                    <h4 class="text-primary">{{ $match['Team2Score'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Games Details -->
    @if(!empty($gameDetails))
        @foreach($gameDetails as $index => $game)
        <div class="card mb-3">
            <div class="card-header">
                <h5>Game {{ $index + 1 }} - {{ \Carbon\Carbon::parse($game['DateTime_UTC'])->format('H:i') }} UTC</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Team Stats -->
                    <div class="col-md-6">
                        @php
                            $isTeam1First = str_contains($game['Team1'], $match['Team1']);
                            $firstTeam = $isTeam1First ? $game['Team1'] : $game['Team2'];
                            $firstTeamPrefix = $isTeam1First ? 'Team1' : 'Team2';
                        @endphp
                        <h6>{{ $firstTeam }}</h6>
                        <ul class="list-unstyled">
                            <li>Gold: {{ number_format($game[$firstTeamPrefix . 'Gold']) }}</li>
                            <li>Kills: {{ $game[$firstTeamPrefix . 'Kills'] }}</li>
                            <li>Towers: {{ $game[$firstTeamPrefix . 'Towers'] }}</li>
                            <li>Dragons: {{ $game[$firstTeamPrefix . 'Dragons'] }}</li>
                            <li>Barons: {{ $game[$firstTeamPrefix . 'Barons'] }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        @php
                            $secondTeam = $isTeam1First ? $game['Team2'] : $game['Team1'];
                            $secondTeamPrefix = $isTeam1First ? 'Team2' : 'Team1';
                        @endphp
                        <h6>{{ $secondTeam }}</h6>
                        <ul class="list-unstyled">
                            <li>Gold: {{ number_format($game[$secondTeamPrefix . 'Gold']) }}</li>
                            <li>Kills: {{ $game[$secondTeamPrefix . 'Kills'] }}</li>
                            <li>Towers: {{ $game[$secondTeamPrefix . 'Towers'] }}</li>
                            <li>Dragons: {{ $game[$secondTeamPrefix . 'Dragons'] }}</li>
                            <li>Barons: {{ $game[$secondTeamPrefix . 'Barons'] }}</li>
                        </ul>
                    </div>

                    <!-- Picks & Bans -->
                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>{{ $firstTeam }} Picks</h6>
                                <p>{{ $game[$firstTeamPrefix . 'Picks'] }}</p>
                                <h6>{{ $firstTeam }} Bans</h6>
                                <p>{{ $game[$firstTeamPrefix . 'Bans'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>{{ $secondTeam }} Picks</h6>
                                <p>{{ $game[$secondTeamPrefix . 'Picks'] }}</p>
                                <h6>{{ $secondTeam }} Bans</h6>
                                <p>{{ $game[$secondTeamPrefix . 'Bans'] }}</p>
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
