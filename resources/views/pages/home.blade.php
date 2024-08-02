@extends('layouts.app')

@section('content')
    <div class="container">
        @if (!empty($todayMatches))
            @php
                $currentTournament = '';
                $tournamentIndex = 0;
            @endphp
            <div class="accordion" id="accordionExample">
                @foreach($todayMatches as $match)
                    @if ($currentTournament !== $match['Name'])
                        @if ($currentTournament !== '')
            </div>
    </div>
    @endif
    @php
        $currentTournament = $match['Name'];
        $tournamentIndex++;
    @endphp
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading{{ $tournamentIndex }}">
            <button class="accordion-button" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $tournamentIndex }}"
                    aria-expanded="true"
                    aria-controls="collapse{{ $tournamentIndex }}">
                {{ $currentTournament }}
            </button>
        </h2>
        <div id="collapse{{ $tournamentIndex }}"
             class="accordion-collapse collapse show"
             aria-labelledby="heading{{ $tournamentIndex }}" data-bs-parent="#accordion{{ $tournamentIndex }}">
            <div class="accordion-body">
                @endif
                <div class="match transition-fast">
                    <div class="status">{{ $match['status'] }}</div>
                    <div class="datetime">{{ date('G:i', strtotime($match['DateTime UTC'])) }}</div>
                    <div class="teams">
                        <div class="team1">
                            <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}"/>
                            {{ $match['Team1'] }}
                        </div>
                        <div class="team2">
                            <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}"/>
                            {{ $match['Team2'] }}
                        </div>
                    </div>
                    <div class="result">
                        <div class="result1">{{ $match['Team1Score'] ?? '-' }}</div>
                        <div class="result2">{{ $match['Team2Score'] ?? '-' }}</div>
                    </div>
                    <div class="stream">
                        @if(!empty($match['Stream']))
                            <a href="{{ $match['Stream'] }}" target="_blank">
                                <img src="{{ asset('img/tv_icon.png') }}" alt="Stream Link">
                            </a>
                        @else
                        @endif
                    </div>
                </div>
                @endforeach
            </div> <!-- Close the last tournament's accordion body -->
        </div> <!-- Close the last accordion item -->
        @else
            <p>No data available.</p>
        @endif
    </div>
@endsection
