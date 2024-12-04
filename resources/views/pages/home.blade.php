@extends('layouts.app')

@php
use App\Http\Controllers\TeamImageController;
use App\Services\APIService;
use App\Helpers\ImageBuilder;

$teamImageController = new TeamImageController(new APIService, new ImageBuilder);
@endphp

@section('content')
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
                       onchange="this.form.submit()"
                       class="date-input">
                <div class="current-date">
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
                             data-bs-target="#tournament{{ $tournamentIndex }}">
                            <div class="tournament-name">
                                {{ $currentTournament }}
                            </div>
                            <i class="bi bi-chevron-down toggle-icon"></i>
                        </div>
                        
                        <div id="tournament{{ $tournamentIndex }}" class="tournament-matches collapse show">
                @endif

                <div class="match-row {{ $match['status'] === 'IS LIVE' ? 'match-live' : '' }}">
                    <a href="{{ route('match.show', ['matchId' => $match['Team1'] . '-vs-' . $match['Team2']]) }}" 
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

<style>
.esports-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.date-navigation {
    background: #1a1a1a;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.date-nav-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
}

.date-nav-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.2em;
    cursor: pointer;
    padding: 5px 10px;
}

.date-selector {
    display: flex;
    align-items: center;
    gap: 10px;
}

.date-input {
    opacity: 0;
    cursor: pointer;
}

.current-date {
    color: #fff;
    font-size: 1.2em;
    font-weight: 500;
    margin-right: 10px;
}

.tournament-section {
    background: #242424;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
}

.tournament-header {
    background: #2c2c2c;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.tournament-name {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #fff;
    font-weight: 500;
}

.tournament-icon {
    width: 24px;
    height: 24px;
}

.match-row {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid #2c2c2c;
    transition: background-color 0.2s;
}

.match-row:hover {
    background: #2c2c2c;
}

.match-live {
    background: rgba(255, 0, 0, 0.1);
}

.match-time {
    width: 80px;
    text-align: center;
}

.live-indicator {
    color: #ff4444;
    font-weight: bold;
    font-size: 0.8em;
    animation: blink 1s infinite;
}

.match-teams {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.team {
    display: flex;
    align-items: center;
    gap: 10px;
}

.team-logo {
    width: 24px;
    height: 24px;
    object-fit: contain;
}

.team-name {
    flex: 1;
    color: #fff;
}

.team-score {
    font-weight: bold;
    color: #fff;
    width: 30px;
    text-align: center;
}

.winner .team-name,
.winner .team-score {
    color: #4CAF50;
}

.match-info {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-left: 20px;
}

.match-format {
    color: #888;
    font-size: 0.9em;
}

.stream-link {
    color: #fff;
    font-size: 1.2em;
}

.stream-link:hover {
    color: #ff4444;
}

@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.no-matches {
    text-align: center;
    padding: 40px;
    color: #888;
}

.no-matches i {
    font-size: 2em;
    margin-bottom: 10px;
}
</style>
@endsection

@push('script')
<script>
function changeDate(days) {
    const dateInput = document.getElementById('dateSelect');
    const currentDate = new Date(dateInput.value);
    currentDate.setDate(currentDate.getDate() + days);
    dateInput.value = currentDate.toISOString().split('T')[0];
    dateInput.form.submit();
}
</script>
@endpush
