@extends('layouts.app')

@section('content')
    <div class="container">
        @if (!empty($todayMatches))
            <div class="league">
                @foreach($todayMatches as $match)
                    <div class="match transition-fast">
                        <div class="status">{{ $match['status'] }}</div>
                        <div class="datetime">{{ date('G:i', strtotime($match['DateTime UTC'])) }}</div>
                        <div class="teams">
                            <div class="team1">
                                @if (!empty($match['Team1ImageUrl']))
                                    <img src="{{ $match['Team1ImageUrl'] }}" alt="{{ $match['Team1'] }}"/>
                                @else
                                    <img src="{{ asset('img/placeholder.png') }}" alt="{{ $match['Team1'] }}"/>
                                @endif
                                {{ $match['Team1'] }}
                            </div>
                            <div class="team2">
                                @if (!empty($match['Team2ImageUrl']))
                                    <img src="{{ $match['Team2ImageUrl'] }}" alt="{{ $match['Team2'] }}"/>
                                @else
                                    <img src="{{ asset('img/placeholder.png') }}" alt="{{ $match['Team2'] }}"/>
                                @endif
                                {{ $match['Team2'] }}
                            </div>
                        </div>
                        <div class="result">
                            <div class="result1">@if($match['Team1Score'] === null)-@else{{ $match['Team1Score'] }}@endif</div>
                            <div class="result1">@if($match['Team2Score'] === null)-@else{{ $match['Team2Score'] }}@endif</div>
                        </div>
                        <div class="stream">
                            <a href="{{ $match['Stream'] }}" target="_blank">
                                <img src="{{ asset('img/tv_icon.png') }}" alt="Stream Link">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No data available.</p>
        @endif
    </div>
@endsection
