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
            <span class="match-date">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('l, F jS, Y') }}</span>
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
            <div class="team-column {{ $match['Winner'] === '1' ? 'winner' : '' }}">
                <a href="{{ route('team.show', ['teamName' => $match['Team1']]) }}">
                    <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}" class="team-logo">
                </a>
                <h2 class="team-name">
                    <a href="{{ route('team.show', ['teamName' => $match['Team1']]) }}" class="team-link">
                        {{ $match['Team1'] }}
                    </a>
                </h2>
                <div class="team-score {{ $match['Winner'] === '1' ? 'winner' : '' }}">
                    {{ $match['Team1Score'] ?? '0' }}
                </div>
            </div>
            
            <div class="match-info-center">
                <div class="match-type">Best of {{ $match['BestOf'] }}</div>
                <div class="match-time">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('H:i') }} UTC</div>
            </div>

            <div class="team-column {{ $match['Winner'] === '2' ? 'winner' : '' }}">
                <a href="{{ route('team.show', ['teamName' => $match['Team2']]) }}">
                    <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}" class="team-logo">
                </a>
                <h2 class="team-name">
                    <a href="{{ route('team.show', ['teamName' => $match['Team2']]) }}" class="team-link">
                        {{ $match['Team2'] }}
                    </a>
                </h2>
                <div class="team-score {{ $match['Winner'] === '2' ? 'winner' : '' }}">
                    {{ $match['Team2Score'] ?? '0' }}
                </div>
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
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/gold.png" alt="Gold" class="stat-icon">
                                    {{ number_format($game['Team1Gold']) }}
                                </span>
                                <span class="stat-label">Gold</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/blue_kills.png" alt="Kills" class="stat-icon">
                                    {{ $game['Team1Kills'] }}
                                </span>
                                <span class="stat-label">Kills</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/blue_tower.png" alt="Towers" class="stat-icon">
                                    {{ $game['Team1Towers'] }}
                                </span>
                                <span class="stat-label">Towers</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/blue_dragon.png" alt="Dragons" class="stat-icon">
                                    {{ $game['Team1Dragons'] }}
                                </span>
                                <span class="stat-label">Dragons</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/blue_baron.png" alt="Barons" class="stat-icon">
                                    {{ $game['Team1Barons'] }}
                                </span>
                                <span class="stat-label">Barons</span>
                            </div>
                        </div>

                        <div class="game-result">
                            <div class="winner-label">{{ $game['WinTeam'] }} Victory</div>
                            <div class="game-length">{{ $game['Gamelength'] }}</div>
                        </div>

                        <div class="team-stats red-side">
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/gold.png" alt="Gold" class="stat-icon">
                                    {{ number_format($game['Team2Gold']) }}
                                </span>
                                <span class="stat-label">Gold</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/red_kills.png" alt="Kills" class="stat-icon">
                                    {{ $game['Team2Kills'] }}
                                </span>
                                <span class="stat-label">Kills</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/red_tower.png" alt="Towers" class="stat-icon">
                                    {{ $game['Team2Towers'] }}
                                </span>
                                <span class="stat-label">Towers</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/red_dragon.png" alt="Dragons" class="stat-icon">
                                    {{ $game['Team2Dragons'] }}
                                </span>
                                <span class="stat-label">Dragons</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-value">
                                    <img src="/storage/icons/ui/red_baron.png" alt="Barons" class="stat-icon">
                                    {{ $game['Team2Barons'] }}
                                </span>
                                <span class="stat-label">Barons</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bans Section -->
                    @php
                        $team1Bans = ChampionIconHelper::formatChampionList($game['Team1Bans']);
                        $team2Bans = ChampionIconHelper::formatChampionList($game['Team2Bans']);
                        $hasBans = !empty(array_filter($team1Bans, fn($ban) => $ban !== 'None')) || 
                                   !empty(array_filter($team2Bans, fn($ban) => $ban !== 'None'));
                    @endphp

                    @if($hasBans)
                        <div class="bans-section">
                            <div class="team-bans blue-side">
                                @foreach($team1Bans as $champion)
                                    @if($champion !== 'None')
                                        <div class="ban-icon" title="{{ $champion }}">
                                            <img src="{{ ChampionIconHelper::getChampionIcon($champion) }}" 
                                                 alt="{{ $champion }}">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="bans-label">Bans</div>
                            <div class="team-bans red-side">
                                @foreach($team2Bans as $champion)
                                    @if($champion !== 'None')
                                        <div class="ban-icon" title="{{ $champion }}">
                                            <img src="{{ ChampionIconHelper::getChampionIcon($champion) }}" 
                                                 alt="{{ $champion }}">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Players Stats Table -->
                    <div class="players-table-container">
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th><img src="/storage/icons/ui/player.png" alt="Player" class="stat-icon">Player</th>
                                    <th><img src="/storage/icons/ui/champion.png" alt="Champion" class="stat-icon">Champion</th>
                                    <th><img src="/storage/icons/ui/kills.png" alt="KDA" class="stat-icon">KDA</th>
                                    <th><img src="/storage/icons/ui/blue_cs.png" alt="CS" class="stat-icon">CS</th>
                                    <th><img src="/storage/icons/ui/gold.png" alt="Gold" class="stat-icon">Gold</th>
                                    <th><img src="/storage/icons/ui/damage.png" alt="Damage" class="stat-icon">Damage</th>
                                    <th><img src="/storage/icons/ui/items.png" alt="Items" class="stat-icon">Items</th>
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

    <!-- Recent Matches Section -->
    <div class="recent-matches-container">
        <!-- Team 1 Recent Matches -->
        <div class="team-recent-matches team1-matches">
            <div class="recent-matches-header">
                <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}" class="recent-matches-team-logo">
                <h3>{{ $match['Team1'] }} Recent Matches</h3>
            </div>
            <div class="matches-list">
                @forelse($team1RecentMatches as $recentMatch)
                    <a href="{{ route('match.show', [
                        'matchId' => $recentMatch['Team1'] . '-vs-' . $recentMatch['Team2'],
                        'date' => \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('Y-m-d')
                    ]) }}" 
                       class="match-item">
                        <div class="match-date">
                            <div class="date">{{ \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('M j, Y') }}</div>
                            <div class="time">{{ \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('H:i') }} UTC</div>
                        </div>
                        <div class="match-teams">
                            <div class="team {{ $recentMatch['Team1'] === $match['Team1'] ? 'current-team' : '' }}">
                                <div class="team-info">
                                    <img src="{{ asset('storage/teamimages/' . $recentMatch['Team1'] . '.png') }}" 
                                         alt="{{ $recentMatch['Team1'] }}" 
                                         class="match-team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name {{ $recentMatch['Winner'] === '1' ? 'winner' : '' }}">
                                        {{ $recentMatch['Team1'] }}
                                    </span>
                                </div>
                                <span class="score {{ $recentMatch['Winner'] === '1' ? 'winner' : '' }}">
                                    {{ $recentMatch['Team1Score'] ?? '-' }}
                                </span>
                            </div>
                            <div class="team {{ $recentMatch['Team2'] === $match['Team1'] ? 'current-team' : '' }}">
                                <div class="team-info">
                                    <img src="{{ asset('storage/teamimages/' . $recentMatch['Team2'] . '.png') }}" 
                                         alt="{{ $recentMatch['Team2'] }}" 
                                         class="match-team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name {{ $recentMatch['Winner'] === '2' ? 'winner' : '' }}">
                                        {{ $recentMatch['Team2'] }}
                                    </span>
                                </div>
                                <span class="score {{ $recentMatch['Winner'] === '2' ? 'winner' : '' }}">
                                    {{ $recentMatch['Team2Score'] ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="match-tournament">
                            {{ $recentMatch['Name'] }}
                        </div>
                    </a>
                @empty
                    <div class="no-matches">No recent matches found</div>
                @endforelse
            </div>
        </div>

        <!-- Team 2 Recent Matches -->
        <div class="team-recent-matches team2-matches">
            <div class="recent-matches-header">
                <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}" class="recent-matches-team-logo">
                <h3>{{ $match['Team2'] }} Recent Matches</h3>
            </div>
            <div class="matches-list">
                @forelse($team2RecentMatches as $recentMatch)
                    <a href="{{ route('match.show', [
                        'matchId' => $recentMatch['Team1'] . '-vs-' . $recentMatch['Team2'],
                        'date' => \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('Y-m-d')
                    ]) }}" 
                       class="match-item">
                        <div class="match-date">
                            <div class="date">{{ \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('M j, Y') }}</div>
                            <div class="time">{{ \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('H:i') }} UTC</div>
                        </div>
                        <div class="match-teams">
                            <div class="team {{ $recentMatch['Team1'] === $match['Team2'] ? 'current-team' : '' }}">
                                <div class="team-info">
                                    <img src="{{ asset('storage/teamimages/' . $recentMatch['Team1'] . '.png') }}" 
                                         alt="{{ $recentMatch['Team1'] }}" 
                                         class="match-team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name {{ $recentMatch['Winner'] === '1' ? 'winner' : '' }}">
                                        {{ $recentMatch['Team1'] }}
                                    </span>
                                </div>
                                <span class="score {{ $recentMatch['Winner'] === '1' ? 'winner' : '' }}">
                                    {{ $recentMatch['Team1Score'] ?? '-' }}
                                </span>
                            </div>
                            <div class="team {{ $recentMatch['Team2'] === $match['Team2'] ? 'current-team' : '' }}">
                                <div class="team-info">
                                    <img src="{{ asset('storage/teamimages/' . $recentMatch['Team2'] . '.png') }}" 
                                         alt="{{ $recentMatch['Team2'] }}" 
                                         class="match-team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name {{ $recentMatch['Winner'] === '2' ? 'winner' : '' }}">
                                        {{ $recentMatch['Team2'] }}
                                    </span>
                                </div>
                                <span class="score {{ $recentMatch['Winner'] === '2' ? 'winner' : '' }}">
                                    {{ $recentMatch['Team2Score'] ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="match-tournament">
                            {{ $recentMatch['Name'] }}
                        </div>
                    </a>
                @empty
                    <div class="no-matches">No recent matches found</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
