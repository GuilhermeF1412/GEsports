@props(['side' => 'blue', 'gold', 'kills', 'towers', 'dragons', 'barons'])

<div class="team-stats {{ $side }}-side">
    <div class="stat-row">
        <span class="stat-value">
            <img src="/storage/icons/ui/gold.png" alt="Gold" class="stat-icon">
            {{ number_format($gold) }}
        </span>
        <span class="stat-label">Gold</span>
    </div>
    <div class="stat-row">
        <span class="stat-value">
            <img src="/storage/icons/ui/{{ $side }}_kills.png" alt="Kills" class="stat-icon">
            {{ $kills }}
        </span>
        <span class="stat-label">Kills</span>
    </div>
    <div class="stat-row">
        <span class="stat-value">
            <img src="/storage/icons/ui/{{ $side }}_tower.png" alt="Towers" class="stat-icon">
            {{ $towers }}
        </span>
        <span class="stat-label">Towers</span>
    </div>
    <div class="stat-row">
        <span class="stat-value">
            <img src="/storage/icons/ui/{{ $side }}_dragon.png" alt="Dragons" class="stat-icon">
            {{ $dragons }}
        </span>
        <span class="stat-label">Dragons</span>
    </div>
    <div class="stat-row">
        <span class="stat-value">
            <img src="/storage/icons/ui/{{ $side }}_baron.png" alt="Barons" class="stat-icon">
            {{ $barons }}
        </span>
        <span class="stat-label">Barons</span>
    </div>
</div> 