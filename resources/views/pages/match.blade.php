@extends('layouts.app')

@php
use App\Helpers\ChampionIconHelper;
use App\Helpers\GameIconHelper;
@endphp

@section('content')
<div class="match-container">
    <!-- Match Header -->
    <div class="match-header">
        <div class="tournament-info">
            <h3>{{ $match['Name'] }}</h3>
            <span class="match-date">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('l, F j, Y') }}</span>
        </div>

        <div class="match-status-bar">
            <div class="match-status {{ $match['status'] === 'IS LIVE' ? 'status-live' : ($match['status'] === 'FINISHED' ? 'status-finished' : 'status-upcoming') }}">
                {{ $match['status'] ?: 'UPCOMING' }}
            </div>
            @if($match['status'] === 'FINISHED')
                @if(!empty($match['Stream']))
                    <a href="{{ $match['Stream'] }}" target="_blank" class="vod-button">
                        <i class="bi bi-play-circle"></i> Watch VOD
                    </a>
                @else
                    <span class="no-vod">No VOD Available</span>
                @endif
            @elseif(!empty($match['Stream']))
                <a href="{{ $match['Stream'] }}" target="_blank" class="stream-button">
                    <i class="bi bi-broadcast"></i> Watch Live
                </a>
            @endif
        </div>

        <div class="teams-overview">
            <div class="team-column">
                <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}" class="team-logo">
                <h2 class="team-name">{{ $match['Team1'] }}</h2>
                <div class="team-score {{ $match['Winner'] === '1' ? 'winner' : '' }}">{{ $match['Team1Score'] ?? '0' }}</div>
            </div>
            
            <div class="match-info-center">
                <div class="match-type">Best of {{ $match['BestOf'] }}</div>
                <div class="match-time">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('H:i') }} UTC</div>
            </div>

            <div class="team-column">
                <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}" class="team-logo">
                <h2 class="team-name">{{ $match['Team2'] }}</h2>
                <div class="team-score {{ $match['Winner'] === '2' ? 'winner' : '' }}">{{ $match['Team2Score'] ?? '0' }}</div>
            </div>
        </div>
    </div>

    <!-- Games Section -->
    @if(!empty($gameDetails))
        <div class="games-section">
            @foreach($gameDetails as $index => $game)
                <div class="game-card">
                    <div class="game-header">
                        <h4>Game {{ $index + 1 }}</h4>
                        <span class="game-time">{{ \Carbon\Carbon::parse($game['DateTime_UTC'])->format('H:i') }} UTC</span>
                    </div>

                    <!-- Team Stats Overview -->
                    <div class="game-overview">
                        <div class="team-stats blue-side">
                            <div class="stat-row">
                                <span class="stat-value">{{ number_format($game['Team1Gold']) }}</span>
                                <span class="stat-label">Gold</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team1Kills'] }}</span>
                                <span class="stat-label">Kills</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team1Towers'] }}</span>
                                <span class="stat-label">Towers</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team1Dragons'] }}</span>
                                <span class="stat-label">Dragons</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team1Barons'] }}</span>
                                <span class="stat-label">Barons</span>
                            </div>
                        </div>

                        <div class="game-result">
                            <div class="winner-label">{{ $game['WinTeam'] }} Victory</div>
                            <div class="game-length">{{ $game['Gamelength'] }}</div>
                        </div>

                        <div class="team-stats red-side">
                            <div class="stat-row">
                                <span class="stat-value">{{ number_format($game['Team2Gold']) }}</span>
                                <span class="stat-label">Gold</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team2Kills'] }}</span>
                                <span class="stat-label">Kills</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team2Towers'] }}</span>
                                <span class="stat-label">Towers</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team2Dragons'] }}</span>
                                <span class="stat-label">Dragons</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">{{ $game['Team2Barons'] }}</span>
                                <span class="stat-label">Barons</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bans Section -->
                    <div class="bans-section">
                        <div class="team-bans blue-side">
                            @foreach(ChampionIconHelper::formatChampionList($game['Team1Bans']) as $champion)
                                <div class="ban-icon" title="{{ $champion }}">
                                    <img src="{{ ChampionIconHelper::getChampionIcon($champion, 'banned') }}" 
                                         alt="{{ $champion }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="bans-label">Bans</div>
                        <div class="team-bans red-side">
                            @foreach(ChampionIconHelper::formatChampionList($game['Team2Bans']) as $champion)
                                <div class="ban-icon" title="{{ $champion }}">
                                    <img src="{{ ChampionIconHelper::getChampionIcon($champion, 'banned') }}" 
                                         alt="{{ $champion }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Players Stats Table -->
                    <div class="players-table-container">
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Champion</th>
                                    <th>KDA</th>
                                    <th>CS</th>
                                    <th>Gold</th>
                                    <th>Damage</th>
                                    <th>Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($game['Team1Players'] as $player)
                                    <tr class="blue-team">
                                        <td class="player-cell">
                                            <span class="player-name">{{ $player['name'] }}</span>
                                            <span class="player-role">{{ $player['role'] }}</span>
                                        </td>
                                        <td class="champion-cell">
                                            <div class="champion-info">
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
                                        </td>
                                        <td class="kda-cell">
                                            {{ $player['kills'] }}/{{ $player['deaths'] }}/{{ $player['assists'] }}
                                        </td>
                                        <td>{{ $player['cs'] }}</td>
                                        <td>{{ number_format($player['gold']) }}</td>
                                        <td>{{ number_format($player['damage']) }}</td>
                                        <td class="items-cell">
                                            <div class="items-grid">
                                                @php
                                                    $items = array_pad(
                                                        array_filter($player['items'], fn($item) => !empty($item)),
                                                        6,
                                                        null
                                                    );
                                                @endphp
                                                @foreach($items as $item)
                                                    <div class="item-slot">
                                                        @if($item)
                                                            <img src="{{ GameIconHelper::getItemIcon($item) }}" 
                                                                 alt="{{ $item }}"
                                                                 title="{{ $item }}">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($game['Team2Players'] as $player)
                                    <tr class="red-team">
                                        <td class="player-cell">
                                            <span class="player-name">{{ $player['name'] }}</span>
                                            <span class="player-role">{{ $player['role'] }}</span>
                                        </td>
                                        <td class="champion-cell">
                                            <div class="champion-info">
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
                                        </td>
                                        <td class="kda-cell">
                                            {{ $player['kills'] }}/{{ $player['deaths'] }}/{{ $player['assists'] }}
                                        </td>
                                        <td>{{ $player['cs'] }}</td>
                                        <td>{{ number_format($player['gold']) }}</td>
                                        <td>{{ number_format($player['damage']) }}</td>
                                        <td class="items-cell">
                                            <div class="items-grid">
                                                @php
                                                    $items = array_pad(
                                                        array_filter($player['items'], fn($item) => !empty($item)),
                                                        6,
                                                        null
                                                    );
                                                @endphp
                                                @foreach($items as $item)
                                                    <div class="item-slot">
                                                        @if($item)
                                                            <img src="{{ GameIconHelper::getItemIcon($item) }}" 
                                                                 alt="{{ $item }}"
                                                                 title="{{ $item }}">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-games">
            <p>No game details available yet.</p>
        </div>
    @endif
</div>
@endsection
