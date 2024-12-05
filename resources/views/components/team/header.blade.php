@props(['team'])

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
                        {{ $team['TeamLocation'] ?? $team['Region'] }}
                        @if(isset($team['Region']) && $team['Region'] && isset($team['TeamLocation']) && $team['TeamLocation'])
                            <span class="region">({{ $team['Region'] }})</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Social Links -->
        @include('components.team.social-links', ['team' => $team])
    </div>
</div> 