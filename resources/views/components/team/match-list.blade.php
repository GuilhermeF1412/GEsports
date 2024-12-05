@props(['matches', 'type', 'teamName', 'title'])

@php
use Carbon\Carbon;
@endphp

<div class="matches-section">
    <h2>{{ $title }}</h2>
    <div class="matches-list">
        @forelse($matches as $index => $match)
            <a href="{{ route('match.show', [
                'matchId' => $match['Team1'] . '-vs-' . $match['Team2'],
                'date' => Carbon::parse($match['DateTime_UTC'])->format('Y-m-d')
            ]) }}" 
               class="match-item {{ $index >= 5 ? 'hidden' : '' }}">
                <div class="match-date">
                    <div class="date">{{ Carbon::parse($match['DateTime_UTC'])->format('M j, Y') }}</div>
                    <div class="time">{{ Carbon::parse($match['DateTime_UTC'])->format('H:i') }} UTC</div>
                </div>
                <div class="match-teams">
                    <div class="team {{ $match['Team1'] === $teamName ? 'current-team' : '' }}">
                        <img src="{{ asset('storage/teamimages/' . $match['Team1'] . '.png') }}" 
                             alt="{{ $match['Team1'] }}" 
                             class="team-logo"
                             onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                        <span class="team-name {{ isset($match['Winner']) && $match['Winner'] === '1' ? 'winner' : '' }}">
                            {{ $match['Team1'] }}
                        </span>
                        <span class="score {{ isset($match['Winner']) && $match['Winner'] === '1' ? 'winner' : '' }}">
                            {{ isset($match['Team1Score']) ? $match['Team1Score'] : '-' }}
                        </span>
                    </div>
                    <div class="team {{ $match['Team2'] === $teamName ? 'current-team' : '' }}">
                        <img src="{{ asset('storage/teamimages/' . $match['Team2'] . '.png') }}" 
                             alt="{{ $match['Team2'] }}" 
                             class="team-logo"
                             onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                        <span class="team-name {{ isset($match['Winner']) && $match['Winner'] === '2' ? 'winner' : '' }}">
                            {{ $match['Team2'] }}
                        </span>
                        <span class="score {{ isset($match['Winner']) && $match['Winner'] === '2' ? 'winner' : '' }}">
                            {{ isset($match['Team2Score']) ? $match['Team2Score'] : '-' }}
                        </span>
                    </div>
                </div>
                <div class="match-tournament">
                    {{ $match['Name'] }}
                </div>
            </a>
        @empty
            <div class="no-matches">
                {{ $type === 'future' ? 'No upcoming matches scheduled' : 'No recent matches found' }}
            </div>
        @endforelse
        @if(count($matches) > 5)
            <button class="show-more-btn" onclick="toggleMatches('{{ $type }}')">
                <i class="bi bi-chevron-down"></i>
                <span class="show-more-text">Show More</span>
            </button>
        @endif
    </div>
</div> 