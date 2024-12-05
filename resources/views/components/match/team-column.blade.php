@props(['team', 'teamImage', 'score', 'isWinner'])

<div class="team-column {{ $isWinner ? 'winner' : '' }}">
    @if($team !== 'TBD')
        <a href="{{ route('team.show', ['teamName' => $team]) }}">
            <img src="{{ $teamImage }}" alt="{{ $team }}" class="team-logo">
        </a>
        <h2 class="team-name">
            <a href="{{ route('team.show', ['teamName' => $team]) }}" class="team-link">
                {{ $team }}
            </a>
        </h2>
    @else
        <img src="{{ $teamImage }}" alt="TBD" class="team-logo">
        <h2 class="team-name">TBD</h2>
    @endif
    <div class="team-score {{ $isWinner ? 'winner' : '' }}">
        {{ $score }}
    </div>
</div> 