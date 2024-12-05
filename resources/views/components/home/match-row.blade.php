@props(['match'])

<div class="match-row {{ $match['isLive'] ? 'match-live' : '' }}">
    <a href="{{ $match['matchUrl'] }}" class="match-link">
        <div class="match-time">
            @if($match['isLive'])
                <div class="live-indicator">LIVE</div>
            @endif
            <div class="time">{{ $match['formattedTime'] }}</div>
        </div>

        <div class="match-teams">
            @foreach(['1', '2'] as $teamNumber)
                @php
                    $teamData = [
                        'name' => $match["Team{$teamNumber}"],
                        'image' => $match["team{$teamNumber}Image"],
                        'score' => $match["team{$teamNumber}Score"],
                        'isWinner' => $match['Winner'] === $teamNumber
                    ];
                @endphp

                <div class="team team-{{ $teamNumber }} {{ $teamData['isWinner'] ? 'winner' : '' }}">
                    <img src="{{ $teamData['image'] }}" 
                         alt="{{ $teamData['name'] }}" 
                         class="team-logo">
                    <span class="team-name">{{ $teamData['name'] }}</span>
                    <span class="team-score">{{ $teamData['score'] }}</span>
                </div>
            @endforeach
        </div>

        <div class="match-info">
            <span class="match-format">Bo{{ $match['BestOf'] }}</span>
            @if(!empty($match['Stream']))
                <a href="{{ $match['Stream'] }}" 
                   target="_blank" 
                   class="stream-link" 
                   onclick="event.stopPropagation();">
                    <i class="bi bi-broadcast"></i>
                </a>
            @endif
        </div>
    </a>
</div> 