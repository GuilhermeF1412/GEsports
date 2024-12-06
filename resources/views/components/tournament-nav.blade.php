@props(['tournaments', 'selectedTournament' => null])

<div class="tournament-nav">
    <div class="tournament-nav-header">
        <h3>Tournaments</h3>
    </div>
    <div class="tournament-list">
        <a href="{{ route('lolhome') }}" 
           class="tournament-item {{ !$selectedTournament ? 'active' : '' }}">
            All Tournaments
        </a>
        @foreach($tournaments as $tournament)
            <a href="{{ route('lolhome', ['tournament' => $tournament['Name']]) }}" 
               class="tournament-item {{ $selectedTournament == $tournament['Name'] ? 'active' : '' }}">
                {{ $tournament['Name'] }}
                <span class="match-count">{{ $tournament['MatchCount'] }}</span>
            </a>
        @endforeach
    </div>
</div> 