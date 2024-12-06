@props(['game', 'videoPath'])

@push('styles')
<style>
    /* Override main container styles for this page */
    body, .page-container, .main-container, main {
        padding: 0 !important;
        margin: 0 !important;
        max-width: none !important;
        overflow-x: hidden !important;
    }

    .content-wrap {
        padding: 0 !important;
    }
</style>
@endpush

<div class="coming-soon-container">
    <video class="video-background" autoplay loop muted playsinline>
        <source src="{{ asset($videoPath) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="content-overlay">
        <div class="warning-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h2>Under Construction</h2>
        <p>The {{ ucfirst(str_replace('_', ' ', $game)) }} section is currently being developed.</p>
        <a href="{{ route('lolhome') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Back to League of Legends
        </a>
    </div>
</div> 