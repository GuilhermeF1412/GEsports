@php
    $dateTime = \Carbon\Carbon::parse($match['DateTime_UTC']);
    $statusClass = match($match['status']) {
        'IS LIVE' => 'status-live',
        'FINISHED' => 'status-finished',
        default => 'status-upcoming'
    };
@endphp

<div class="match-header">
    <div class="tournament-info">
        <h3>{{ $match['Name'] }}</h3>
        <span class="match-date">{{ $dateTime->format('l, F jS, Y') }}</span>
    </div>

    <div class="match-status-bar">
        <div class="match-status {{ $statusClass }}">
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
        @foreach(['1', '2'] as $teamNumber)
            @php
                $teamName = $match["Team{$teamNumber}"];
                $teamImage = $match["Team{$teamNumber}Image"];
                $teamScore = $match["Team{$teamNumber}Score"] ?? '0';
                $isWinner = $match['Winner'] === $teamNumber;
            @endphp

            <div class="team-column {{ $isWinner ? 'winner' : '' }}">
                @if($teamName !== 'TBD')
                    <a href="{{ route('team.show', ['teamName' => $teamName]) }}">
                        <img src="{{ $teamImage }}" alt="{{ $teamName }}" class="team-logo">
                    </a>
                    <h2 class="team-name">
                        <a href="{{ route('team.show', ['teamName' => $teamName]) }}" class="team-link">
                            {{ $teamName }}
                        </a>
                    </h2>
                @else
                    <img src="{{ $teamImage }}" alt="TBD" class="team-logo">
                    <h2 class="team-name">TBD</h2>
                @endif
                <div class="team-score {{ $isWinner ? 'winner' : '' }}">
                    {{ $teamScore }}
                </div>
            </div>

            @if($teamNumber === '1')
                <div class="match-info-center">
                    <div class="match-type">Best of {{ $match['BestOf'] }}</div>
                    <div class="match-time">{{ $dateTime->format('H:i') }} UTC</div>
                </div>
            @endif
        @endforeach
    </div>
</div> 