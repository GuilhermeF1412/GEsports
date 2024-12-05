<div class="container-fluid topbar-custom d-flex align-items-center justify-content-between">
    <a href="{{ route('lolhome') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('logo/logo.svg') }}" alt="">
    </a>

    <div class="d-flex align-items-center">
        <!-- Search Bar -->
        <div class="search-bar d-none d-md-flex me-3">
            <div class="search-form d-flex align-items-center">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search Teams" 
                       autocomplete="off">
                <button type="button"><i class="bi bi-search"></i></button>
                <div id="searchResults" class="search-results"></div>
            </div>
        </div><!-- End Search Bar -->
    </div>
</div><!-- End of Topbar -->
