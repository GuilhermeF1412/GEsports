document.addEventListener('DOMContentLoaded', () => {
    // Initialize search if search input exists
    if (document.getElementById('searchInput')) {
        initializeSearch();
    }

    // Initialize match controls if on team page
    if (document.querySelector('.matches-section')) {
        initializeMatchControls();
    }

    // Initialize date navigation if on home page
    if (document.getElementById('dateSelect')) {
        initializeDateNavigation();
    }
});

// Search functionality
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

searchInput?.addEventListener('input', function() {
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
    if (searchInput && searchResults && !searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// Match controls functionality
function toggleMatches(type) {
    const section = document.querySelector(`.matches-section:${type === 'future' ? 'first-of-type' : 'last-of-type'}`);
    const items = section.querySelectorAll('.match-item');
    const btn = section.querySelector('.show-more-btn');
    const btnText = btn.querySelector('.show-more-text');
    const btnIcon = btn.querySelector('i');
    
    items.forEach((item, index) => {
        if (index >= 5) {
            item.classList.toggle('hidden');
        }
    });
    
    const isExpanded = !items[5]?.classList.contains('hidden');
    btnText.textContent = isExpanded ? 'Show Less' : 'Show More';
    btnIcon.className = isExpanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
}

// Date navigation functionality
function changeDate(days) {
    const dateInput = document.getElementById('dateSelect');
    const currentDate = new Date(dateInput.value);
    currentDate.setDate(currentDate.getDate() + days);
    dateInput.value = currentDate.toISOString().split('T')[0];
    dateInput.form.submit();
} 