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
