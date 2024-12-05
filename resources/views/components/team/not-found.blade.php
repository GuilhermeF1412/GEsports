@props([])

<div class="team-not-found">
    <div class="error-message">
        <i class="bi bi-exclamation-circle"></i>
        <h2>Team Not Found</h2>
        <p>Sorry, we couldn't find information for this team.</p>
        <a href="{{ route('lolhome') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Back to Home
        </a>
    </div>
</div> 