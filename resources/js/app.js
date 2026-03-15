import 'dropzone/dist/dropzone.css';
import './bootstrap';
import { conceptEditFormData } from './concept-edit-form';
import './concept-form';
import './dropzone-config';
import './product-form';
import { productFormData, productFormWithConceptData } from './product-form';
import './realtime-search';

// Import Select2 wrapper (handles jQuery and Select2 initialization)
import 'select2/dist/css/select2.min.css';
import './select2-wrapper';

// Now import select2-init (which uses jQuery and Select2)
import './select2-init';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.data('conceptEditForm', conceptEditFormData);
Alpine.data('productFormData', productFormData);
Alpine.data('productFormWithConceptData', productFormWithConceptData);

Alpine.start();
