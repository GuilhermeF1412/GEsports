@php
    $currentTournament = '';
@endphp

@foreach($matches as $match)
    @if ($currentTournament !== $match['Name'])
        @if ($currentTournament !== '')
            </div> <!-- Close tournament-matches -->
        @endif
        
        @php $currentTournament = $match['Name']; @endphp
        
        <div class="tournament-section">
            <div class="tournament-header" 
                 data-bs-toggle="collapse" 
                 data-bs-target="#tournament{{ $match['tournamentIndex'] }}"
                 aria-expanded="true"
                 aria-controls="tournament{{ $match['tournamentIndex'] }}">
                <div class="tournament-name">
                    {{ $currentTournament }}
                </div>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </div>
            
            <div id="tournament{{ $match['tournamentIndex'] }}" 
                 class="tournament-matches collapse show"
                 aria-labelledby="tournament{{ $match['tournamentIndex'] }}">
    @endif

    @include('components.home.match-row', ['match' => $match])
@endforeach

@if (!empty($currentTournament))
    </div> <!-- Close last tournament-matches -->
@endif 