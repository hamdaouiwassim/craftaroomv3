import './bootstrap';
import './dropzone-config';
import 'dropzone/dist/dropzone.css';
import './product-form';
import './concept-form';

// Import Select2 wrapper (handles jQuery and Select2 initialization)
import './select2-wrapper';
import 'select2/dist/css/select2.min.css';

// Now import select2-init (which uses jQuery and Select2)
import './select2-init';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
