@php
    $tableHeaders = [
        'player' => ['icon' => 'player.png', 'label' => 'Player'],
        'champion' => ['icon' => 'champion.png', 'label' => 'Champion'],
        'kda' => ['icon' => 'kills.png', 'label' => 'KDA'],
        'cs' => ['icon' => 'blue_cs.png', 'label' => 'CS'],
        'gold' => ['icon' => 'gold.png', 'label' => 'Gold'],
        'damage' => ['icon' => 'damage.png', 'label' => 'Damage'],
        'items' => ['icon' => 'items.png', 'label' => 'Items']
    ];
@endphp

<div class="players-table-container">
    <table class="players-table">
        <thead>
            <tr>
                @foreach($tableHeaders as $header)
                    <th>
                        <img src="/storage/icons/ui/{{ $header['icon'] }}" 
                             alt="{{ $header['label'] }}" 
                             class="stat-icon">
                        {{ $header['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach(['Team1Players' => 'blue', 'Team2Players' => 'red'] as $team => $side)
                @foreach($game[$team] as $player)
                    <tr class="{{ $side }}-team">
                        <td class="player-cell">
                            <span class="player-name">{{ $player['name'] }}</span>
                            <span class="player-role">{{ $player['role'] }}</span>
                        </td>
                        <td class="champion-cell">
                            @include('components.match.player-champion', [
                                'player' => $player,
                                'ChampionIconHelper' => $ChampionIconHelper,
                                'GameIconHelper' => $GameIconHelper
                            ])
                        </td>
                        <td class="kda-cell">
                            {{ $player['kills'] }}/{{ $player['deaths'] }}/{{ $player['assists'] }}
                        </td>
                        <td>{{ $player['cs'] }}</td>
                        <td>{{ number_format($player['gold']) }}</td>
                        <td>{{ number_format($player['damage']) }}</td>
                        <td class="items-cell">
                            @include('components.match.player-items', [
                                'items' => $player['items'],
                                'GameIconHelper' => $GameIconHelper
                            ])
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div> 