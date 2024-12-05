@extends('layouts.app')

@section('content')
<div class="team-page">
    <div class="team-container">
        @if(isset($team) && $team)
            @include('components.team.header', ['team' => $team])

            <!-- Future Matches -->
            @include('components.team.matches-section', [
                'title' => 'Future Matches',
                'matches' => $futureMatches ?? [],
                'type' => 'future',
                'team' => $team
            ])

            <!-- Recent Matches -->
            @include('components.team.matches-section', [
                'title' => 'Recent Matches',
                'matches' => $recentMatches ?? [],
                'type' => 'recent',
                'team' => $team
            ])
        @else
            @include('components.team.not-found')
        @endif
    </div>
</div>
@endsection 