@php
    $socialLinks = [];

    // Only add social links that exist in the team data
    if (isset($team['Website'])) {
        $socialLinks['Website'] = ['icon' => 'bi-globe', 'url' => $team['Website']];
    }

    if (isset($team['Twitter'])) {
        $socialLinks['Twitter'] = ['icon' => 'bi-twitter-x', 'url' => "https://twitter.com/{$team['Twitter']}"];
    }

    if (isset($team['Youtube'])) {
        $socialLinks['Youtube'] = ['icon' => 'bi-youtube', 'url' => $team['Youtube']];
    }

    if (isset($team['Facebook'])) {
        $socialLinks['Facebook'] = ['icon' => 'bi-facebook', 'url' => $team['Facebook']];
    }

    if (isset($team['Instagram'])) {
        $socialLinks['Instagram'] = ['icon' => 'bi-instagram', 'url' => "https://instagram.com/{$team['Instagram']}"];
    }

    if (isset($team['Discord'])) {
        $socialLinks['Discord'] = ['icon' => 'bi-discord', 'url' => $team['Discord']];
    }
@endphp

<div class="social-links">
    @foreach($socialLinks as $platform => $data)
        <a href="{{ $data['url'] }}" 
           target="_blank" 
           class="social-link" 
           title="{{ $platform }}">
            <i class="bi {{ $data['icon'] }}"></i>
        </a>
    @endforeach
</div> 