@extends('layouts.app')

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
                        {{ $formattedDate }}
                    </div>
                </form>
                
                <button type="button" class="date-nav-btn" onclick="changeDate(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Matches Container -->
        <div class="matches-container">
            @if (!empty($matches))
                @php $currentTournament = ''; @endphp
                
                @foreach($matches as $match)
                    @if ($currentTournament !== $match['Name'])
                        @if ($currentTournament !== '')
                            </div> <!-- Close tournament-matches -->
                        @endif
                        
                        @php $currentTournament = $match['Name']; @endphp
                        
                        <div class="tournament-section">
                            <div class="tournament-header" data-bs-toggle="collapse" 
                                 data-bs-target="#tournament{{ $match['tournamentIndex'] }}"
                                 aria-expanded="true"
                                 aria-controls="tournament{{ $match['tournamentIndex'] }}">
                                <div class="tournament-name">
                                    {{ $currentTournament }}
                                </div>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </div>
                            
                            <div id="tournament{{ $match['tournamentIndex'] }}" 
                                 class="tournament-matches collapse show"
                                 aria-labelledby="tournament{{ $match['tournamentIndex'] }}">
                    @endif

                    <div class="match-row {{ $match['isLive'] ? 'match-live' : '' }}">
                        <a href="{{ $match['matchUrl'] }}" class="match-link">
                            <div class="match-time">
                                @if($match['isLive'])
                                    <div class="live-indicator">LIVE</div>
                                @endif
                                <div class="time">{{ $match['formattedTime'] }}</div>
                            </div>

                            <div class="match-teams">
                                <div class="team team-1 {{ $match['Winner'] === '1' ? 'winner' : '' }}">
                                    <img src="{{ $match['team1Image'] }}" 
                                         alt="{{ $match['Team1'] }}" class="team-logo">
                                    <span class="team-name">{{ $match['Team1'] }}</span>
                                    <span class="team-score">{{ $match['team1Score'] }}</span>
                                </div>
                                <div class="team team-2 {{ $match['Winner'] === '2' ? 'winner' : '' }}">
                                    <img src="{{ $match['team2Image'] }}" 
                                         alt="{{ $match['Team2'] }}" class="team-logo">
                                    <span class="team-name">{{ $match['Team2'] }}</span>
                                    <span class="team-score">{{ $match['team2Score'] }}</span>
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
</div>
@endsection

