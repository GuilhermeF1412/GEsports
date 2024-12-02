@extends('layouts.app')

@php
use App\Helpers\ChampionIconHelper;
use App\Helpers\GameIconHelper;
@endphp

@section('content')
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Blue Side (Team1) -->
                <div class="col-md-4 text-center">
                    <div class="side-label blue-side mb-2">Blue Side</div>
                    <img src="{{ $match['Team1Image'] }}" alt="{{ $match['Team1'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team1'] }}</h3>
                    <h4 class="blue-side">{{ $match['Team1Score'] }}</h4>
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

                <!-- Red Side (Team2) -->
                <div class="col-md-4 text-center">
                    <div class="side-label red-side mb-2">Red Side</div>
                    <img src="{{ $match['Team2Image'] }}" alt="{{ $match['Team2'] }}" class="img-fluid rounded-circle mb-2" style="max-width: 150px;">
                    <h3>{{ $match['Team2'] }}</h3>
                    <h4 class="red-side">{{ $match['Team2Score'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Games Details -->
    @if(!empty($gameDetails))
        @foreach($gameDetails as $index => $game)
        <div class="card mb-3 match-details-container">
            <div class="card-header">
                <h5>Game {{ $index + 1 }} - {{ \Carbon\Carbon::parse($game['DateTime_UTC'])->format('H:i') }} UTC</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Team Stats Row -->
                    <div class="row mb-4">
                        <!-- Blue Side Stats -->
                        <div class="col-md-5">
                            <h6 class="blue-side">
                                <i class="bi bi-circle-fill me-2"></i>
                                <img src="{{ $game['Team1Image'] }}" alt="{{ $game['Team1'] }}" class="team-icon me-2">
                                {{ $game['Team1'] }} (Blue Side)
                            </h6>
                            <ul class="list-unstyled">
                                <li>Gold: {{ number_format($game['Team1Gold']) }}</li>
                                <li>Kills: {{ $game['Team1Kills'] }}</li>
                                <li>Towers: {{ $game['Team1Towers'] }}</li>
                                <li>Dragons: {{ $game['Team1Dragons'] }}</li>
                                <li>Barons: {{ $game['Team1Barons'] }}</li>
                            </ul>
                        </div>

                        <div class="col-md-2">
                            <!-- Remove bans container from here -->
                        </div>

                        <!-- Red Side Stats -->
                        <div class="col-md-5">
                            <h6 class="red-side">
                                <i class="bi bi-circle-fill me-2"></i>
                                <img src="{{ $game['Team2Image'] }}" alt="{{ $game['Team2'] }}" class="team-icon me-2">
                                {{ $game['Team2'] }} (Red Side)
                            </h6>
                            <ul class="list-unstyled">
                                <li>Gold: {{ number_format($game['Team2Gold']) }}</li>
                                <li>Kills: {{ $game['Team2Kills'] }}</li>
                                <li>Towers: {{ $game['Team2Towers'] }}</li>
                                <li>Dragons: {{ $game['Team2Dragons'] }}</li>
                                <li>Barons: {{ $game['Team2Barons'] }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Add bans above the table -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="text-center blue-side">
                                <h6 class="mb-3">Blue Side Bans</h6>
                                <div class="d-flex justify-content-center gap-2">
                                    @foreach(ChampionIconHelper::formatChampionList($game['Team1Bans']) as $champion)
                                        <div class="champion-icon" title="{{ $champion }}">
                                            <img src="{{ ChampionIconHelper::getChampionIcon($champion, 'banned') }}" 
                                                 alt="{{ $champion }}" 
                                                 class="img-fluid" 
                                                 style="width: 50px; height: 50px; opacity: 0.7;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center red-side">
                                <h6 class="mb-3">Red Side Bans</h6>
                                <div class="d-flex justify-content-center gap-2">
                                    @foreach(ChampionIconHelper::formatChampionList($game['Team2Bans']) as $champion)
                                        <div class="champion-icon" title="{{ $champion }}">
                                            <img src="{{ ChampionIconHelper::getChampionIcon($champion, 'banned') }}" 
                                                 alt="{{ $champion }}" 
                                                 class="img-fluid" 
                                                 style="width: 50px; height: 50px; opacity: 0.7;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Player Stats Table -->
                    <div class="col-12">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">CS</th>
                                    <th class="text-center">Gold</th>
                                    <th class="text-center">Damage</th>
                                    <th class="text-center">KDA</th>
                                    <th class="text-end">Blue Side</th>
                                    <th class="text-center">Champion</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Champion</th>
                                    <th>Red Side</th>
                                    <th class="text-center">KDA</th>
                                    <th class="text-center">Damage</th>
                                    <th class="text-center">Gold</th>
                                    <th class="text-center">CS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_array($game['Team1Players']) && is_array($game['Team2Players']))
                                    @php
                                        $roles = ['Top', 'Jungle', 'Mid', 'Bot', 'Support'];
                                        foreach ($roles as $role) {
                                            $bluePlayer = collect($game['Team1Players'])->firstWhere('role', $role);
                                            $redPlayer = collect($game['Team2Players'])->firstWhere('role', $role);
                                    @endphp
                                        <tr>
                                            <td class="text-center blue-side">
                                                @php
                                                    // Get items array and pad it to 6 slots
                                                    $items = array_pad(
                                                        array_filter($bluePlayer['items'] ?? [], fn($item) => !empty($item)), // Remove empty slots
                                                        6,  // Total length
                                                        null  // Fill with null
                                                    );
                                                @endphp
                                                <div class="items-grid">
                                                    <div class="d-flex justify-content-center gap-1 mb-1">
                                                        @for ($i = 0; $i < 3; $i++)
                                                            <div class="item-icon">
                                                                @if ($items[$i])
                                                                    <img src="{{ GameIconHelper::getItemIcon($items[$i]) }}" 
                                                                         alt="{{ $items[$i] }}"
                                                                         title="{{ $items[$i] }}"
                                                                         style="width: 25px; height: 25px;">
                                                                @else
                                                                    <img src="/storage/items/7050.png" 
                                                                         alt="Empty Slot"
                                                                         title="Empty Slot"
                                                                         style="width: 25px; height: 25px; opacity: 0.3;">
                                                                @endif
                                                            </div>
                                                        @endfor
                                                    </div>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        @for ($i = 3; $i < 6; $i++)
                                                            <div class="item-icon">
                                                                @if ($items[$i])
                                                                    <img src="{{ GameIconHelper::getItemIcon($items[$i]) }}" 
                                                                         alt="{{ $items[$i] }}"
                                                                         title="{{ $items[$i] }}"
                                                                         style="width: 25px; height: 25px;">
                                                                @else
                                                                    <img src="/storage/items/7050.png" 
                                                                         alt="Empty Slot"
                                                                         title="Empty Slot"
                                                                         style="width: 25px; height: 25px; opacity: 0.3;">
                                                                @endif
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center blue-side">{{ $bluePlayer['cs'] }}</td>
                                            <td class="text-center blue-side">{{ number_format($bluePlayer['damage']) }}</td>
                                            <td class="text-center blue-side">{{ $bluePlayer['kills'] }}/{{ $bluePlayer['deaths'] }}/{{ $bluePlayer['assists'] }}</td>
                                            <td class="text-end blue-side">{{ $bluePlayer['name'] }}</td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <img src="{{ ChampionIconHelper::getChampionIcon($bluePlayer['champion']) }}" 
                                                         alt="{{ $bluePlayer['champion'] }}"
                                                         title="{{ $bluePlayer['champion'] }}"
                                                         style="width: 40px; height: 40px;">
                                                    <div class="summoner-spells ms-1">
                                                        @foreach($bluePlayer['summonerSpells'] as $spell)
                                                            <div class="mb-1">
                                                                <img src="{{ GameIconHelper::getSummonerSpellIcon($spell) }}" 
                                                                     alt="{{ $spell }}"
                                                                     title="{{ $spell }}"
                                                                     style="width: 20px; height: 20px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $role }}</td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="summoner-spells me-1">
                                                        @foreach($redPlayer['summonerSpells'] as $spell)
                                                            <div class="mb-1">
                                                                <img src="{{ GameIconHelper::getSummonerSpellIcon($spell) }}" 
                                                                     alt="{{ $spell }}"
                                                                     title="{{ $spell }}"
                                                                     style="width: 20px; height: 20px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <img src="{{ ChampionIconHelper::getChampionIcon($redPlayer['champion']) }}" 
                                                         alt="{{ $redPlayer['champion'] }}"
                                                         title="{{ $redPlayer['champion'] }}"
                                                         style="width: 40px; height: 40px;">
                                                </div>
                                            </td>
                                            <td class="red-side">{{ $redPlayer['name'] }}</td>
                                            <td class="text-center red-side">{{ $redPlayer['kills'] }}/{{ $redPlayer['deaths'] }}/{{ $redPlayer['assists'] }}</td>
                                            <td class="text-center red-side">{{ number_format($redPlayer['damage']) }}</td>
                                            <td class="text-center red-side">{{ number_format($redPlayer['gold']) }}</td>
                                            <td class="text-center red-side">{{ $redPlayer['cs'] }}</td>
                                            <td class="text-center red-side">
                                                <div class="items-grid">
                                                    <div class="d-flex justify-content-center gap-1 mb-1">
                                                        @for ($i = 0; $i < 3; $i++)
                                                            <div class="item-icon">
                                                                @if (isset($redPlayer['items'][$i]) && $redPlayer['items'][$i])
                                                                    <img src="{{ GameIconHelper::getItemIcon($redPlayer['items'][$i]) }}" 
                                                                         alt="{{ $redPlayer['items'][$i] }}"
                                                                         title="{{ $redPlayer['items'][$i] }}"
                                                                         style="width: 25px; height: 25px;">
                                                                @else
                                                                    <div class="empty-item" style="width: 25px; height: 25px; background: #eee;"></div>
                                                                @endif
                                                            </div>
                                                        @endfor
                                                    </div>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        @for ($i = 3; $i < 6; $i++)
                                                            <div class="item-icon">
                                                                @if (isset($redPlayer['items'][$i]) && $redPlayer['items'][$i])
                                                                    <img src="{{ GameIconHelper::getItemIcon($redPlayer['items'][$i]) }}" 
                                                                         alt="{{ $redPlayer['items'][$i] }}"
                                                                         title="{{ $redPlayer['items'][$i] }}"
                                                                         style="width: 25px; height: 25px;">
                                                                @else
                                                                    <div class="empty-item" style="width: 25px; height: 25px; background: #eee;"></div>
                                                                @endif
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @php
                                        }
                                    @endphp
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">No game details available yet.</div>
    @endif
</div>
@endsection
