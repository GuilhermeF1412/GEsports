<!-- resources/views/layouts/subtopbar.blade.php -->

<div class="container-fluid subtopbar-custom d-flex align-items-center justify-content-between">
    <nav class="header-nav mx-auto">
        <ul class="nav d-flex align-items-center">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('lolhome') ? 'active' : '' }}" href="{{ route('lolhome') }}">
                    <img src="{{ asset('icons/league_of_legends.svg') }}" alt="League of Legends Icon" class="icon">
                    <span>League of Legends</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('valhome') ? 'active' : '' }}" href="{{ route('valhome') }}">
                    <img src="{{ asset('icons/valorant.svg') }}" alt="Valorant Icon" class="icon">
                    <span>Valorant</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cs2home') ? 'active' : '' }}" href="{{ route('cs2home') }}">
                    <img src="{{ asset('icons/cs2.svg') }}" alt="CS2 Icon" class="icon">
                    <span>CS2</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dota2home') ? 'active' : '' }}" href="{{ route('dota2home') }}">
                    <img src="{{ asset('icons/dota_2.svg') }}" alt="Dota 2 Icon" class="icon">
                    <span>Dota 2</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('rocketleaguehome') ? 'active' : '' }}" href="{{ route('rocketleaguehome') }}">
                    <img src="{{ asset('icons/rocket_league.svg') }}" alt="Rocket League Icon" class="icon">
                    <span>Rocket League</span>
                </a>
            </li>
        </ul>
    </nav><!-- End Icons Navigation -->
</div><!-- End Subtopbar -->
