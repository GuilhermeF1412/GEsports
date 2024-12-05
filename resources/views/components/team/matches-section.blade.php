@php
    $matches = $matches ?? [];
    $maxVisibleMatches = 5;
@endphp

<div class="matches-section">
    <h2>{{ $title }}</h2>
    <div class="matches-list">
        @forelse($matches as $index => $match)
            <a href="{{ route('match.show', [
                'matchId' => $match['Team1'] . '-vs-' . $match['Team2'],
                'date' => \Carbon\Carbon::parse($match['DateTime_UTC'])->format('Y-m-d')
            ]) }}" 
               class="match-item {{ $index >= $maxVisibleMatches ? 'hidden' : '' }}">
                
                @include('components.team.match-item', [
                    'match' => $match,
                    'team' => $team,
                    'type' => $type
                ])
            </a>
        @empty
            <div class="no-matches">
                {{ $type === 'future' ? 'No upcoming matches scheduled' : 'No recent matches found' }}
            </div>
        @endforelse

        @if(count($matches) > $maxVisibleMatches)
            <button class="show-more-btn" onclick="toggleMatches('{{ $type }}')">
                <i class="bi bi-chevron-down"></i>
                <span class="show-more-text">Show More</span>
            </button>
        @endif
    </div>
</div> 