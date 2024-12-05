@extends('layouts.app')

@section('content')
<div class="team-page">
    <div class="team-container">
        @if($team)
            <!-- Team Header -->
            <div class="team-header">
                <div class="team-info">
                    <div class="team-main">
                        <img src="{{ asset('storage/teamimages/' . $team['Name'] . '.png') }}" 
                             alt="{{ $team['Name'] }}" 
                             class="team-logo"
                             onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                        <div class="team-details">
                            <h1>{{ $team['Name'] }}</h1>
                            @if((isset($team['TeamLocation']) && $team['TeamLocation']) || (isset($team['Region']) && $team['Region']))
                                <div class="team-location">
                                    <i class="bi bi-geo-alt"></i>
                                    @if(isset($team['TeamLocation']) && $team['TeamLocation'])
                                        {{ $team['TeamLocation'] }}
                                    @else
                                        {{ $team['Region'] }}
                                    @endif
                                    @if(isset($team['Region']) && $team['Region'])
                                        @if(isset($team['TeamLocation']) && $team['TeamLocation'])
                                            <span class="region">({{ $team['Region'] }})</span>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="social-links">
                        @if(isset($team['Website']) && $team['Website'])
                            <a href="{{ $team['Website'] }}" target="_blank" class="social-link" title="Website">
                                <i class="bi bi-globe"></i>
                            </a>
                        @endif
                        @if(isset($team['Twitter']) && $team['Twitter'])
                            <a href="https://twitter.com/{{ $team['Twitter'] }}" target="_blank" class="social-link" title="Twitter">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                        @endif
                        @if(isset($team['Youtube']) && $team['Youtube'])
                            <a href="{{ $team['Youtube'] }}" target="_blank" class="social-link" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                        @endif
                        @if(isset($team['Facebook']) && $team['Facebook'])
                            <a href="{{ $team['Facebook'] }}" target="_blank" class="social-link" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                        @endif
                        @if(isset($team['Instagram']) && $team['Instagram'])
                            <a href="https://instagram.com/{{ $team['Instagram'] }}" target="_blank" class="social-link" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                        @endif
                        @if(isset($team['Discord']) && $team['Discord'])
                            <a href="{{ $team['Discord'] }}" target="_blank" class="social-link" title="Discord">
                                <i class="bi bi-discord"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Future Matches -->
            <div class="matches-section">
                <h2>Future Matches</h2>
                <div class="matches-list">
                    @forelse($futureMatches as $index => $match)
                        <a href="{{ route('match.show', [
                            'matchId' => $match['Team1'] . '-vs-' . $match['Team2'],
                            'date' => \Carbon\Carbon::parse($match['DateTime_UTC'])->format('Y-m-d')
                        ]) }}" 
                           class="match-item {{ $index >= 5 ? 'hidden' : '' }}">
                            <div class="match-date">
                                <div class="date">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('M j, Y') }}</div>
                                <div class="time">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('H:i') }} UTC</div>
                            </div>
                            <div class="match-teams">
                                <div class="team {{ $match['Team1'] === $team['Name'] ? 'current-team' : '' }}">
                                    <img src="{{ asset('storage/teamimages/' . $match['Team1'] . '.png') }}" 
                                         alt="{{ $match['Team1'] }}" 
                                         class="team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name">{{ $match['Team1'] }}</span>
                                    <span class="score">-</span>
                                </div>
                                <div class="team {{ $match['Team2'] === $team['Name'] ? 'current-team' : '' }}">
                                    <img src="{{ asset('storage/teamimages/' . $match['Team2'] . '.png') }}" 
                                         alt="{{ $match['Team2'] }}" 
                                         class="team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name">{{ $match['Team2'] }}</span>
                                    <span class="score">-</span>
                                </div>
                            </div>
                            <div class="match-tournament">
                                {{ $match['Name'] }}
                            </div>
                        </a>
                    @empty
                        <div class="no-matches">
                            No upcoming matches scheduled
                        </div>
                    @endforelse
                    @if(count($futureMatches) > 5)
                        <button class="show-more-btn" onclick="toggleMatches('future')">
                            <i class="bi bi-chevron-down"></i>
                            <span class="show-more-text">Show More</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Recent Matches -->
            <div class="matches-section">
                <h2>Recent Matches</h2>
                <div class="matches-list">
                    @forelse($recentMatches as $index => $recentMatch)
                        <a href="{{ route('match.show', [
                            'matchId' => $recentMatch['Team1'] . '-vs-' . $recentMatch['Team2'],
                            'date' => \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('Y-m-d')
                        ]) }}" 
                           class="match-item {{ $index >= 5 ? 'hidden' : '' }}">
                            <div class="match-date">
                                <div class="date">{{ \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('M j, Y') }}</div>
                                <div class="time">{{ \Carbon\Carbon::parse($recentMatch['DateTime_UTC'])->format('H:i') }} UTC</div>
                            </div>
                            <div class="match-teams">
                                <div class="team {{ $recentMatch['Team1'] === $team['Name'] ? 'current-team' : '' }}">
                                    <img src="{{ asset('storage/teamimages/' . $recentMatch['Team1'] . '.png') }}" 
                                         alt="{{ $recentMatch['Team1'] }}" 
                                         class="team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name {{ $recentMatch['Winner'] === '1' ? 'winner' : '' }}">
                                        {{ $recentMatch['Team1'] }}
                                    </span>
                                    @if($recentMatch['Team1Score'] !== null)
                                        <span class="score {{ $recentMatch['Winner'] === '1' ? 'winner' : '' }}">
                                            {{ $recentMatch['Team1Score'] }}
                                        </span>
                                    @else
                                        <span class="score">-</span>
                                    @endif
                                </div>
                                <div class="team {{ $recentMatch['Team2'] === $team['Name'] ? 'current-team' : '' }}">
                                    <img src="{{ asset('storage/teamimages/' . $recentMatch['Team2'] . '.png') }}" 
                                         alt="{{ $recentMatch['Team2'] }}" 
                                         class="team-logo"
                                         onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
                                    <span class="team-name {{ $recentMatch['Winner'] === '2' ? 'winner' : '' }}">
                                        {{ $recentMatch['Team2'] }}
                                    </span>
                                    @if($recentMatch['Team2Score'] !== null)
                                        <span class="score {{ $recentMatch['Winner'] === '2' ? 'winner' : '' }}">
                                            {{ $recentMatch['Team2Score'] }}
                                        </span>
                                    @else
                                        <span class="score">-</span>
                                    @endif
                                </div>
                            </div>
                            <div class="match-tournament">
                                {{ $recentMatch['Name'] }}
                            </div>
                        </a>
                    @empty
                        <div class="no-matches">
                            No recent matches found
                        </div>
                    @endforelse
                    @if(count($recentMatches) > 5)
                        <button class="show-more-btn" onclick="toggleMatches('recent')">
                            <i class="bi bi-chevron-down"></i>
                            <span class="show-more-text">Show More</span>
                        </button>
                    @endif
                </div>
            </div>
        @else
            <div class="team-not-found">
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <h2>Team Not Found</h2>
                    <p>Sorry, we couldn't find information for this team.</p>
                    <a href="{{ route('lolhome') }}" class="back-button">
                        <i class="bi bi-arrow-left"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('script')
<script>
function toggleMatches(type) {
    const section = document.querySelector(`.matches-section:${type === 'future' ? 'first-of-type' : 'last-of-type'}`);
    const items = section.querySelectorAll('.match-item');
    const btn = section.querySelector('.show-more-btn');
    const btnText = btn.querySelector('.show-more-text');
    const btnIcon = btn.querySelector('i');
    
    // Toggle visibility of matches
    items.forEach((item, index) => {
        if (index >= 5) {
            item.classList.toggle('hidden');
        }
    });
    
    // Check if we're showing or hiding matches
    const isExpanded = !items[5]?.classList.contains('hidden');
    
    // Update button text and icon
    btnText.textContent = isExpanded ? 'Show Less' : 'Show More';
    btnIcon.className = isExpanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
}
</script>
@endpush
@endsection 