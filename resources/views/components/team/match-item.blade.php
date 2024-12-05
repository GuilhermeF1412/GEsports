@php
    $dateTime = \Carbon\Carbon::parse($match['DateTime_UTC']);
    $isRecentMatch = $type === 'recent';
@endphp

<div class="match-date">
    <div class="date">{{ $dateTime->format('M j, Y') }}</div>
    <div class="time">{{ $dateTime->format('H:i') }} UTC</div>
</div>

<div class="match-teams">
    @foreach(['1', '2'] as $teamNumber)
        @php
            $currentTeam = $match["Team{$teamNumber}"];
            $isCurrentTeam = $currentTeam === $team['Name'];
            $score = $match["Team{$teamNumber}Score"] ?? null;
            $isWinner = $isRecentMatch && $match['Winner'] === $teamNumber;
        @endphp

        <div class="team {{ $isCurrentTeam ? 'current-team' : '' }}">
            <img src="{{ asset('storage/teamimages/' . $currentTeam . '.png') }}" 
                 alt="{{ $currentTeam }}" 
                 class="team-logo"
                 onerror="this.src='{{ asset('storage/teamimages/placeholder.png') }}'">
            
            <span class="team-name {{ $isWinner ? 'winner' : '' }}">
                {{ $currentTeam }}
            </span>
            
            <span class="score {{ $isWinner ? 'winner' : '' }}">
                {{ $score !== null ? $score : '-' }}
            </span>
        </div>
    @endforeach
</div>

<div class="match-tournament">
    {{ $match['Name'] }}
</div> 