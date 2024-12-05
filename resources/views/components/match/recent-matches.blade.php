@props(['teamName', 'teamImage', 'recentMatches', 'currentTeam'])

<div class="team-recent-matches">
    <div class="recent-matches-header">
        <img src="{{ $teamImage }}" alt="{{ $teamName }}" class="recent-matches-team-logo">
        <h3>{{ $teamName }} Recent Matches</h3>
    </div>
    <div class="matches-list">
        @forelse($recentMatches as $recentMatch)
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
                    @foreach(['1', '2'] as $teamNumber)
                        @php
                            $team = $recentMatch["Team{$teamNumber}"];
                            $isCurrentTeam = $team === $currentTeam;
                            $score = $recentMatch["Team{$teamNumber}Score"] ?? null;
                            $isWinner = $recentMatch['Winner'] === $teamNumber;
                        @endphp

                        <div class="team {{ $isCurrentTeam ? 'current-team' : '' }}">
                            <div class="team-info">
                                <img src="{{ asset('storage/teamimages/' . $team . '.png') }}" 
                                     alt="{{ $team }}" 
                                     class="match-team-logo"
                                     onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                <span class="team-name {{ $isWinner ? 'winner' : '' }}">
                                    {{ $team }}
                                </span>
                            </div>
                            <span class="score {{ $isWinner ? 'winner' : '' }}">
                                {{ $score ?? '-' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </a>
        @empty
            <div class="no-matches">No recent matches found</div>
        @endforelse
    </div>
</div> 