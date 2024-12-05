@php
    $socialLinks = [
        'Website' => ['icon' => 'bi-globe', 'url' => $team['Website'] ?? null],
        'Twitter' => ['icon' => 'bi-twitter-x', 'url' => $team['Twitter'] ? "https://twitter.com/{$team['Twitter']}" : null],
        'Youtube' => ['icon' => 'bi-youtube', 'url' => $team['Youtube'] ?? null],
        'Facebook' => ['icon' => 'bi-facebook', 'url' => $team['Facebook'] ?? null],
        'Instagram' => ['icon' => 'bi-instagram', 'url' => $team['Instagram'] ? "https://instagram.com/{$team['Instagram']}" : null],
        'Discord' => ['icon' => 'bi-discord', 'url' => $team['Discord'] ?? null],
    ];
@endphp

<div class="social-links">
    @foreach($socialLinks as $platform => $data)
        @if($data['url'])
            <a href="{{ $data['url'] }}" 
               target="_blank" 
               class="social-link" 
               title="{{ $platform }}">
                <i class="bi {{ $data['icon'] }}"></i>
            </a>
        @endif
    @endforeach
</div> 