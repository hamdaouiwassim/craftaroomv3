/**
 * Select2 Wrapper
 * Ensures Select2 is properly attached to jQuery
 * 
 * Select2 uses UMD pattern. When bundled by Vite, we need to manually
 * initialize it by calling the factory function.
 */

import $ from 'jquery';

// Make jQuery available globally - Select2's UMD wrapper checks for this
window.$ = window.jQuery = $;

// Import Select2 CSS
import 'select2/dist/css/select2.min.css';

// Import Select2 synchronously - Vite will handle the UMD wrapper
// Select2's main export is a factory function: function(root, jQuery) { factory(jQuery); return jQuery; }
import select2Factory from 'select2';

// Immediately call the factory function to attach Select2 to jQuery
// This must happen synchronously so Select2 is available when needed
if (select2Factory) {
    let factory = select2Factory;
    
    // Handle different export patterns
    if (typeof factory !== 'function') {
        factory = factory.default || factory.select2 || factory;
    }
    
    // Call the factory function with window and jQuery
    if (typeof factory === 'function') {
        try {
            factory(window, $);
            console.log('✓ Select2 factory called');
        } catch (e) {
            console.error('Error calling Select2 factory:', e);
        }
    } else {
        console.error('Select2 factory is not a function. Type:', typeof factory);
    }
}

// Also use dynamic import as fallback (runs in parallel)
import('select2').then((select2Module) => {
    // Only use this if the sync import didn't work
    if (typeof $.fn.select2 === 'undefined') {
        let factory = select2Module.default || select2Module;
        if (factory && typeof factory !== 'function') {
            factory = factory.default || factory.select2 || factory;
        }
        if (typeof factory === 'function') {
            factory(window, $);
            console.log('✓ Select2 attached via dynamic import');
        }
    }
}).catch(err => {
    console.error('Dynamic Select2 import failed:', err);
});

// Verify Select2 is attached
setTimeout(() => {
    if (typeof $.fn.select2 !== 'undefined') {
        console.log('✓ Select2 successfully attached to jQuery');
    } else {
        console.error('✗ Select2 failed to attach to jQuery');
        console.log('Select2 factory:', select2Factory);
        console.log('jQuery.fn methods:', Object.keys($.fn).slice(0, 15));
    }
}, 150);

export default $;

