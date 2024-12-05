@extends('layouts.app')
@php
use App\Http\Controllers\TeamImageController;
use App\Services\APIService;
use App\Helpers\ImageBuilder;

$teamImageController = new TeamImageController(new APIService, new ImageBuilder);
@endphp

@section('content')
<div class="home-page">
    <div class="esports-container">
        <!-- Date Navigation Bar -->
        <div class="date-navigation">
            <div class="date-nav-container">
                <button type="button" class="date-nav-btn" onclick="changeDate(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>
                
                <form action="{{ route('lolhome') }}" method="GET" class="date-selector">
                    <input type="date" name="date" id="dateSelect" 
                           value="{{ $selectedDate }}" 
                           style="display: none;">
                    <div class="current-date" id="currentDate">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}
                    </div>
                </form>
                
                <button type="button" class="date-nav-btn" onclick="changeDate(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Matches Container -->
        <div class="matches-container">
            @if (!empty($todayMatches))
                @php
                    $currentTournament = '';
                    $tournamentIndex = 0;
                @endphp
                
                @foreach($todayMatches as $match)
                    @if ($currentTournament !== $match['Name'])
                        @if ($currentTournament !== '')
                            </div> <!-- Close tournament-matches -->
                        @endif
                        
                        @php
                            $currentTournament = $match['Name'];
                            $tournamentIndex++;
                        @endphp
                        
                        <div class="tournament-section">
                            <div class="tournament-header" data-bs-toggle="collapse" 
                                 data-bs-target="#tournament{{ $tournamentIndex }}"
                                 aria-expanded="true"
                                 aria-controls="tournament{{ $tournamentIndex }}">
                                <div class="tournament-name">
                                    {{ $currentTournament }}
                                </div>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </div>
                            
                            <div id="tournament{{ $tournamentIndex }}" 
                                 class="tournament-matches collapse show"
                                 aria-labelledby="tournament{{ $tournamentIndex }}">
                @endif

                <div class="match-row {{ $match['status'] === 'IS LIVE' ? 'match-live' : '' }}">
                    <a href="{{ route('match.show', [
                        'matchId' => $match['Team1'] . '-vs-' . $match['Team2'],
                        'date' => $selectedDate
                    ]) }}" 
                       class="match-link">
                        <div class="match-time">
                            @if($match['status'] === 'IS LIVE')
                                <div class="live-indicator">LIVE</div>
                            @endif
                            <div class="time">{{ date('H:i', strtotime($match['DateTime_UTC'])) }}</div>
                        </div>

                        <div class="match-teams">
                            <div class="team team-1 {{ $match['Winner'] === '1' ? 'winner' : '' }}">
                                <img src="{{ $teamImageController->getTeamImagePath($match['Team1OverviewPage']) }}" 
                                     alt="{{ $match['Team1'] }}" class="team-logo">
                                <span class="team-name">{{ $match['Team1'] }}</span>
                                <span class="team-score">{{ $match['Team1Score'] ?? '-' }}</span>
                            </div>
                            <div class="team team-2 {{ $match['Winner'] === '2' ? 'winner' : '' }}">
                                <img src="{{ $teamImageController->getTeamImagePath($match['Team2OverviewPage']) }}" 
                                     alt="{{ $match['Team2'] }}" class="team-logo">
                                <span class="team-name">{{ $match['Team2'] }}</span>
                                <span class="team-score">{{ $match['Team2Score'] ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="match-info">
                            <span class="match-format">Bo{{ $match['BestOf'] }}</span>
                            @if(!empty($match['Stream']))
                                <a href="{{ $match['Stream'] }}" target="_blank" class="stream-link" onclick="event.stopPropagation();">
                                    <i class="bi bi-broadcast"></i>
                                </a>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
            
            @if (!empty($currentTournament))
                </div> <!-- Close last tournament-matches -->
            @endif
        @else
            <div class="no-matches">
                <i class="bi bi-calendar-x"></i>
                <p>No matches scheduled for this date</p>
            </div>
        @endif
    </div>
</div>
@endsection

