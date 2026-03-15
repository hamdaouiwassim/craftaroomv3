/**
 * Real-time Search Component
 * Usage: Add x-data="realtimeSearch()" to your search container
 */
window.realtimeSearch = function(config = {}) {
    return {
        searchQuery: '',
        results: [],
        isLoading: false,
        endpoint: config.endpoint || window.location.pathname,
        searchParam: config.searchParam || 'search',
        rowSelector: config.rowSelector || 'table tbody tr',
        useAjax: config.useAjax === true,
        debounceTimer: null,
        
        init() {
            // Initialize with existing search value from URL
            const urlParams = new URLSearchParams(window.location.search);
            this.searchQuery = urlParams.get(this.searchParam) || '';
            
            if (this.searchQuery) {
                this.performSearch();
            }
        },
        
        onSearchInput() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.performSearch();
            }, 300); // 300ms debounce
        },
        
        async performSearch() {
            const query = this.searchQuery.trim();

            // Default behavior: client-side row filtering (no page reload).
            if (!this.useAjax) {
                this.filterVisibleRows(query);
                this.syncUrl(query);
                return;
            }

            this.isLoading = true;

            try {
                const url = new URL(this.endpoint, window.location.origin);
                url.searchParams.set(this.searchParam, query);
                url.searchParams.set('_ajax', '1');

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Search failed');

                const data = await response.json();
                this.results = data.results || data;
                this.syncUrl(query);
            } catch (error) {
                console.error('Search error:', error);
            } finally {
                this.isLoading = false;
            }
        },

        filterVisibleRows(query) {
            const rows = document.querySelectorAll(this.rowSelector);
            const normalizedQuery = query.toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const searchText = (row.dataset.searchName || row.textContent || '').toLowerCase();
                const matches = !normalizedQuery || searchText.includes(normalizedQuery);
                row.style.display = matches ? '' : 'none';
                if (matches) {
                    visibleCount += 1;
                }
            });

            this.results = Array.from({ length: visibleCount });
        },

        syncUrl(query) {
            const currentUrl = new URL(window.location.href);
            if (query) {
                currentUrl.searchParams.set(this.searchParam, query);
            } else {
                currentUrl.searchParams.delete(this.searchParam);
            }
            window.history.replaceState({}, '', currentUrl);
        },
        
        clearSearch() {
            this.searchQuery = '';
            this.results = [];
            this.filterVisibleRows('');
            this.syncUrl('');
        }
    };
};

/**
 * Simple Real-time Table Filter
 * For client-side filtering of already-loaded data
 */
window.tableFilter = function() {
    return {
        searchQuery: '',
        filteredItems: [],
        allItems: [],
        
        init(items = []) {
            this.allItems = items;
            this.filteredItems = items;
        },
        
        filter() {
            if (!this.searchQuery.trim()) {
                this.filteredItems = this.allItems;
                return;
            }
            
            const query = this.searchQuery.toLowerCase();
            this.filteredItems = this.allItems.filter(item => {
                // Search across all string properties
                return Object.values(item).some(value => {
                    if (typeof value === 'string') {
                        return value.toLowerCase().includes(query);
                    }
                    return false;
                });
            });
        },
        
        onInput() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.filter();
            }, 200);
        }
    };
};
