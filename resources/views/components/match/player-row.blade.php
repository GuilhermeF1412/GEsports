@props(['player', 'team'])

<tr class="{{ $team }}-team">
    <td class="player-cell">
        <span class="player-name">{{ $player['name'] }}</span>
        <span class="player-role">{{ $player['role'] }}</span>
    </td>
    <td class="champion-cell">
        <x-match.champion-info :player="$player" />
    </td>
    <td class="kda-cell">
        {{ $player['kills'] }}/{{ $player['deaths'] }}/{{ $player['assists'] }}
    </td>
    <td>{{ $player['cs'] }}</td>
    <td>{{ number_format($player['gold']) }}</td>
    <td>{{ number_format($player['damage']) }}</td>
    <td class="items-cell">
        <x-match.items-grid :items="$player['items']" />
    </td>
</tr> 