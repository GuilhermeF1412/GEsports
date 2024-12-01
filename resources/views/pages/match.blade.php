@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Team 1 -->
                <div class="col-md-4 text-center">
                    <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team1'] }}</h3>
                    <h4 class="text-primary">{{ $match['Team1Score'] }}</h4>
                </div>

                <!-- Match Info -->
                <div class="col-md-4 text-center">
                    <div class="match-status mb-3">
                        @if($match['status'] === 'IS LIVE')
                            <span class="badge bg-danger">LIVE</span>
                        @elseif($match['status'] === 'FINISHED')
                            <span class="badge bg-secondary">FINISHED</span>
                        @else
                            <span class="badge bg-primary">UPCOMING</span>
                        @endif
                    </div>
                    <div class="match-time">
                        <p class="mb-1">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('F j, Y') }}</p>
                        <p class="mb-1">{{ \Carbon\Carbon::parse($match['DateTime_UTC'])->format('H:i') }} UTC</p>
                    </div>
                    <div class="match-details mt-3">
                        <p class="mb-1">Best of {{ $match['BestOf'] }}</p>
                        @if($match['Stream'])
                            <a href="{{ $match['Stream'] }}" target="_blank" class="btn btn-sm btn-primary mt-2">Watch Stream</a>
                        @endif
                    </div>
                </div>

                <!-- Team 2 -->
                <div class="col-md-4 text-center">
                    <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team2'] }}</h3>
                    <h4 class="text-primary">{{ $match['Team2Score'] }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
