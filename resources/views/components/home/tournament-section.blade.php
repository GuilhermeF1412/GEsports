@props(['tournament', 'matches'])

<div class="tournament-section">
    <div class="tournament-header" data-bs-toggle="collapse" 
         data-bs-target="#tournament{{ $tournament['index'] }}"
         aria-expanded="true"
         aria-controls="tournament{{ $tournament['index'] }}">
        <div class="tournament-name">
            {{ $tournament['name'] }}
        </div>
        <i class="bi bi-chevron-down toggle-icon"></i>
    </div>
    
    <div id="tournament{{ $tournament['index'] }}" 
         class="tournament-matches collapse show"
         aria-labelledby="tournament{{ $tournament['index'] }}">
        @foreach($matches as $match)
            <x-home.match-row :match="$match" />
        @endforeach
    </div>
</div> 