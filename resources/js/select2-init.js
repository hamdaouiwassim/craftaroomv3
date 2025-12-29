/**
 * Select2 Initialization
 * Handles Select2 initialization for multiple select fields
 */

// Flag to prevent multiple simultaneous initializations
let isInitializing = false;
let isInitialized = false;

export function initSelect2() {
    // Prevent multiple simultaneous calls
    if (isInitializing) {
        return false;
    }

    if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
        console.warn('jQuery is not loaded. Select2 cannot be initialized.');
        return false;
    }

    const $ = window.$;

    // Check if Select2 is available - wait if not ready yet
    if (typeof $.fn.select2 === 'undefined') {
        console.warn('Select2 is not yet loaded. Waiting...');
        // Retry after a short delay
        setTimeout(() => {
            if (typeof $.fn.select2 !== 'undefined') {
                initSelect2();
            } else {
                console.error('Select2 failed to load after waiting');
            }
        }, 200);
        return false;
    }

    // Check if elements exist and are visible
    const roomsSelect = document.getElementById('rooms');
    const metalsSelect = document.getElementById('metals');
    
    if (!roomsSelect || !metalsSelect) {
        return false;
    }

    // Check if already initialized and still valid
    if (isInitialized) {
        const roomsInitialized = $('#rooms').hasClass('select2-hidden-accessible');
        const metalsInitialized = $('#metals').hasClass('select2-hidden-accessible');
        if (roomsInitialized && metalsInitialized) {
            return true; // Already initialized, no need to reinitialize
        }
    }

    isInitializing = true;

    try {
        // Destroy existing Select2 instances to avoid duplicates
        if ($('#rooms').hasClass('select2-hidden-accessible')) {
            $('#rooms').select2('destroy');
        }
        if ($('#metals').hasClass('select2-hidden-accessible')) {
            $('#metals').select2('destroy');
        }

        // Ensure the selects have both id and name attributes
        if (!roomsSelect.id) roomsSelect.id = 'rooms';
        if (!roomsSelect.name) roomsSelect.name = 'rooms[]';
        if (!metalsSelect.id) metalsSelect.id = 'metals';
        if (!metalsSelect.name) metalsSelect.name = 'metals[]';
        
        // Initialize Select2 on rooms field
        $(roomsSelect).select2({
            placeholder: 'Sélectionner des pièces',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                }
            }
        });

        // Initialize Select2 on metals field
        $(metalsSelect).select2({
            placeholder: 'Sélectionner des métaux',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                }
            }
        });

        isInitialized = true;
        return true;
    } catch (error) {
        console.error('Error initializing Select2:', error);
        return false;
    } finally {
        isInitializing = false;
    }
}

// Initialize Select2 when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait for both Alpine.js and Select2 to be ready
    const checkAndInit = () => {
        if (typeof window.$ !== 'undefined' && typeof window.$.fn.select2 !== 'undefined') {
            // Wait a bit more for Alpine.js to initialize
            setTimeout(() => {
                initSelect2();
            }, 300);
        } else {
            // Retry if Select2 isn't ready yet (max 10 times)
            let retries = 0;
            const maxRetries = 10;
            const retryCheck = () => {
                if (typeof window.$ !== 'undefined' && typeof window.$.fn.select2 !== 'undefined') {
                    setTimeout(() => {
                        initSelect2();
                    }, 300);
                } else if (retries < maxRetries) {
                    retries++;
                    setTimeout(retryCheck, 100);
                }
            };
            setTimeout(retryCheck, 100);
        }
    };
    checkAndInit();
});

// Make initSelect2 available globally
window.initSelect2 = initSelect2;

