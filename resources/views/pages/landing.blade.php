<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEsports - Your Esports Hub</title>
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <img src="{{ asset('logo/logo.svg') }}" alt="GEsports Logo" class="logo">
            <h1>Welcome to GEsports</h1>
            <div class="description">
                <p>Your comprehensive hub for esports statistics, match schedules, and live updates. 
                   Track your favorite teams, watch upcoming matches, and stay updated with the latest 
                   tournament information across multiple gaming titles.</p>
            </div>
        </header>

        <div class="games-grid">
            <a href="{{ route('lolhome') }}" class="game-card active">
                <img src="{{ asset('icons/league_of_legends.svg') }}" alt="League of Legends">
                <h3>League of Legends</h3>
                <p>Live matches, tournament coverage, and team statistics for professional LoL esports.</p>
            </a>

            <a href="{{ route('valhome') }}" class="game-card coming-soon">
                <img src="{{ asset('icons/valorant.svg') }}" alt="Valorant">
                <h3>Valorant</h3>
                <p>Follow professional Valorant tournaments and teams.</p>
            </a>

            <a href="{{ route('cs2home') }}" class="game-card coming-soon">
                <img src="{{ asset('icons/cs2.svg') }}" alt="CS2">
                <h3>CS2</h3>
                <p>Stay updated with professional Counter-Strike 2 matches and events.</p>
            </a>

            <a href="{{ route('dota2home') }}" class="game-card coming-soon">
                <img src="{{ asset('icons/dota_2.svg') }}" alt="Dota 2">
                <h3>Dota 2</h3>
                <p>Track professional Dota 2 tournaments and team performances.</p>
            </a>

            <a href="{{ route('rocketleaguehome') }}" class="game-card coming-soon">
                <img src="{{ asset('icons/rocket_league.svg') }}" alt="Rocket League">
                <h3>Rocket League</h3>
                <p>Follow professional Rocket League championships and teams.</p>
            </a>
        </div>
    </div>

    <footer>
        <div class="text-center">
            <div>PROJECT MADE BY GUILHERME FERNANDES</div>
            <div>GESPORTS <img src="{{ asset('logo/logo.svg') }}" alt=""></div>
        </div>
    </footer>
</body>
</html> 