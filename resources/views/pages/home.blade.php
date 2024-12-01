@extends('layouts.app')

@php
use App\Http\Controllers\TeamImageController;
use App\Services\APIService;
use App\Helpers\ImageBuilder;

$teamImageController = new TeamImageController(new APIService, new ImageBuilder);
@endphp

@section('content')
    <div class="container">
        <!-- Date Selection -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('home') }}" method="GET" class="d-flex align-items-center justify-content-between">
                            <button type="button" class="btn btn-outline-primary" onclick="changeDate(-1)">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            
                            <input type="date" name="date" id="dateSelect" class="form-control mx-2" 
                                   value="{{ $selectedDate }}" onchange="this.form.submit()">
                            
                            <button type="button" class="btn btn-outline-primary" onclick="changeDate(1)">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (!empty($todayMatches))
            @php
                $currentTournament = '';
                $tournamentIndex = 0;
            @endphp
            <div class="accordion" id="accordionExample">
                @foreach($todayMatches as $match)
                    @if ($currentTournament !== $match['Name'])
                        @if ($currentTournament !== '')
                            </div> <!-- Close accordion-body -->
                        </div> <!-- Close accordion-collapse -->
                    </div> <!-- Close accordion-item -->
                        @endif
                        @php
                            $currentTournament = $match['Name'];
                            $tournamentIndex++;
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $tournamentIndex }}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ $tournamentIndex }}" aria-expanded="true" 
                                        aria-controls="collapse{{ $tournamentIndex }}">
                                    {{ $currentTournament }}
                                </button>
                            </h2>
                            <div id="collapse{{ $tournamentIndex }}" class="accordion-collapse collapse show" 
                                 aria-labelledby="heading{{ $tournamentIndex }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                    @endif
                    <div class="match transition-fast">
                        <a href="{{ route('match.show', ['matchId' => $match['Team1'] . '-vs-' . $match['Team2']]) }}" class="match-link" style="text-decoration: none; color: inherit;">
                            @php
                                \Log::info("Match data for " . $match['Team1'] . " vs " . $match['Team2'] . ":");
                                \Log::info("Team1OverviewPage: " . ($match['Team1OverviewPage'] ?? 'null'));
                                \Log::info("Team2OverviewPage: " . ($match['Team2OverviewPage'] ?? 'null'));
                            @endphp
                            <div class="status">{{ $match['status'] }}</div>
                            <div class="datetime">{{ date('G:i', strtotime($match['DateTime_UTC'])) }}</div>
                            <div class="teams">
                                <div class="team1">
                                    <img src="{{ $teamImageController->getTeamImagePath($match['Team1OverviewPage']) }}" alt="{{ $match['Team1'] }}">
                                    {{ $match['Team1'] }}
                                </div>
                                <div class="team2">
                                    <img src="{{ $teamImageController->getTeamImagePath($match['Team2OverviewPage']) }}" alt="{{ $match['Team2'] }}">
                                    {{ $match['Team2'] }}
                                </div>
                            </div>
                            <div class="result">
                                <div class="result1">{{ $match['Team1Score'] ?? '-' }}</div>
                                <div class="result2">{{ $match['Team2Score'] ?? '-' }}</div>
                            </div>
                            <div class="stream">
                                @if(!empty($match['Stream']))
                                    <a href="{{ $match['Stream'] }}" target="_blank" onclick="event.stopPropagation();">
                                        <img src="{{ asset('img/tv_icon.png') }}" alt="Stream Link">
                                    </a>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
                @if (!empty($currentTournament))
                    </div> <!-- Close last accordion-body -->
                    </div> <!-- Close last accordion-collapse -->
                    </div> <!-- Close last accordion-item -->
                @endif
            </div>
        @else
            <p>No data available.</p>
        @endif
    </div>
@endsection

@push('script')
<script>
function changeDate(days) {
    const dateInput = document.getElementById('dateSelect');
    const currentDate = new Date(dateInput.value);
    currentDate.setDate(currentDate.getDate() + days);
    dateInput.value = currentDate.toISOString().split('T')[0];
    dateInput.form.submit();
}
</script>
@endpush
