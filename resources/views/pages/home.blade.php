@extends('layouts.app')

@section('content')
<div class="home-page">
    <div class="esports-container">
        @include('components.home.date-navigation', [
            'selectedDate' => $selectedDate,
            'formattedDate' => $formattedDate
        ])

        <!-- Matches Container -->
        <div class="matches-container">
            @if (!empty($matches))
                @include('components.home.tournament-matches', ['matches' => $matches])
            @else
                @include('components.home.no-matches')
            @endif
        </div>
    </div>
</div>
@endsection

