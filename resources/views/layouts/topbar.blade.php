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
                       placeholder="Search teams or matches..." 
                       autocomplete="off">
                <button type="button"><i class="bi bi-search"></i></button>
                <div id="searchResults" class="search-results"></div>
            </div>
        </div><!-- End Search Bar -->

        <!-- Profile Dropdown or Login/Signup -->
        <nav class="header-nav">
            <ul class="d-flex align-items-center mb-0">
                @auth
                    <!-- Authenticated User -->
                    <li class="nav-item dropdown pe-3">
                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                           data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                        </a><!-- End Profile Image Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>{{ Auth::user()->name }}</h6>
                                <span>{{ Auth::user()->email }}</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </li>
                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->
                @else
                    <!-- Guest User -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                    </li>
                @endauth
            </ul>
        </nav><!-- End Icons Navigation -->
    </div>
</div><!-- End of Topbar -->

@push('script')
<script>
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.style.display = 'none';
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`/api/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                
                if (data.length === 0) {
                    searchResults.innerHTML = '<div class="no-results">No results found</div>';
                } else {
                    data.forEach(item => {
                        const resultItem = document.createElement('a');
                        resultItem.href = item.url;
                        resultItem.className = 'search-result-item';
                        
                        resultItem.innerHTML = `
                            <img src="${item.image}" alt="${item.name}">
                            <span>${item.name}</span>
                            <span class="result-type">${item.type}</span>
                        `;
                        
                        searchResults.appendChild(resultItem);
                    });
                }
                
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.innerHTML = '<div class="no-results">Error performing search</div>';
                searchResults.style.display = 'block';
            });
    }, 300);
});

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});
</script>
@endpush
