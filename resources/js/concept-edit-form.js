/**
 * Concept Edit Form – Stepper and validation for edit concept (designer + admin).
 * 5 steps: Informations, Détails, Médias, Mesures, Vérification.
 * Form submits natively (PUT); no create/upload flow.
 */
console.log('Concept Edit Form loaded');
console.log('Concept Edit Form data:', {
    totalSteps: 5,
    stepLabels: {
        1: 'Informations',
        2: 'Détails',
        3: 'Médias',
        4: 'Mesures',
        5: 'Vérification',
    },
});
export function conceptEditFormData() {
    const totalSteps = 5;
    const stepLabels = {
        1: 'Informations',
        2: 'Détails',
        3: 'Médias',
        4: 'Mesures',
        5: 'Vérification',
    };

    return {
        currentStep: 1,
        totalSteps,
        stepLabels,
        errorMessage: '',
        photosError: '',
        modelError: '',

        get steps() {
            return Array.from({ length: this.totalSteps }, (_, i) => i + 1);
        },

        nextStep() {
            this.errorMessage = '';
            this.photosError = '';
            this.modelError = '';
            if (this.validateStep(this.currentStep)) {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                    if (this.currentStep === 2 && typeof window.initSelect2 === 'function') {
                        setTimeout(() => window.initSelect2(), 200);
                    }
                    if (this.currentStep === 5 && typeof this.updateReview === 'function') {
                        this.updateReview();
                    }
                }
            }
        },

        prevStep() {
            if (this.currentStep > 1) this.currentStep--;
        },

        validateStep(step) {
            if (step === 1) {
                const name = document.getElementById('name')?.value?.trim();
                const category = document.getElementById('category_id')?.value;
                if (!name || !category) {
                    this.errorMessage = 'Veuillez remplir le nom et la catégorie.';
                    return false;
                }
            }
            if (step === 2) {
                const description = document.getElementById('description')?.value?.trim();
                const roomsSelect = document.getElementById('rooms');
                const metalsSelect = document.getElementById('metals');
                const rooms = roomsSelect ? Array.from(roomsSelect.selectedOptions).map((o) => o.value) : [];
                const metals = metalsSelect ? Array.from(metalsSelect.selectedOptions).map((o) => o.value) : [];
                if (!description || rooms.length === 0 || metals.length === 0) {
                    this.errorMessage = 'Veuillez remplir la description et sélectionner au moins une pièce et un métal.';
                    return false;
                }
            }
            if (step === 3) {
                this.photosError = '';
                this.modelError = '';
                if (!this.validateMedia()) return false;
            }
            if (step === 4) {
                const measureSize = document.getElementById('measure_size')?.value;
                const length = document.getElementById('length')?.value;
                const width = document.getElementById('width')?.value;
                const height = document.getElementById('height')?.value;
                const weightValue = document.getElementById('weight_value')?.value;
                const weightUnit = document.getElementById('weight_unit')?.value;
                const hasSize = !!measureSize;
                const hasDimensions = !!(length && width && height);
                const hasWeight = !!(weightValue && weightUnit);
                if (!hasSize && !hasDimensions && !hasWeight) {
                    this.errorMessage = 'Veuillez renseigner au moins une mesure (taille, dimensions ou poids).';
                    return false;
                }
            }
            return true;
        },

        goToStep(step) {
            this.errorMessage = '';
            this.photosError = '';
            this.modelError = '';
            if (step < this.currentStep || step === 1) {
                this.currentStep = step;
                if (step === 2 && typeof window.initSelect2 === 'function') {
                    setTimeout(() => window.initSelect2(), 200);
                }
                if (step === 5 && typeof this.updateReview === 'function') this.updateReview();
            } else if (step === this.currentStep + 1 && this.validateStep(this.currentStep)) {
                this.currentStep = step;
                if (step === 2 && typeof window.initSelect2 === 'function') {
                    setTimeout(() => window.initSelect2(), 200);
                }
                if (step === 5 && typeof this.updateReview === 'function') this.updateReview();
            }
        },

        updateReview() {
            setTimeout(() => {
                const set = (id, text) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = text != null && text !== '' ? String(text) : '-';
                };
                const nameInput = document.getElementById('name');
                const categorySelect = document.getElementById('category_id');
                const descInput = document.getElementById('description');
                const roomsSelect = document.getElementById('rooms');
                const metalsSelect = document.getElementById('metals');
                const measureSizeEl = document.getElementById('measure_size');
                const lengthEl = document.getElementById('length');

                set('review-name', nameInput?.value);
                set('review-category', categorySelect?.selectedIndex >= 0 ? categorySelect.options[categorySelect.selectedIndex].text : '-');
                set('review-description', descInput?.value ? (descInput.value.length > 80 ? descInput.value.slice(0, 80) + '…' : descInput.value) : '-');
                const roomsCount = roomsSelect ? roomsSelect.selectedOptions.length : 0;
                const metalsCount = metalsSelect ? metalsSelect.selectedOptions.length : 0;
                set('review-rooms', roomsCount ? roomsCount + ' pièce(s)' : '-');
                set('review-metals', metalsCount ? metalsCount + ' métal(aux)' : '-');
                set('review-measures', measureSizeEl?.value || (lengthEl?.value ? 'Dimensions / Poids' : '-'));

                const photosEl = document.getElementById('review-photos');
                const modelEl = document.getElementById('review-model');
                if (photosEl) photosEl.textContent = (window.photosDropzone?.files?.length || 0) + ' photo(s)';
                if (modelEl) modelEl.textContent = (window.modelDropzone?.files?.length || 0) > 0 ? 'Oui' : 'Non';
            }, 100);
        },

        validateMedia() {
            const photosErrors = [];
            const modelErrors = [];
            const PHOTOS_MAX = 10;
            const PHOTO_MAX_SIZE = 5 * 1024 * 1024; // 5MB
            const PHOTO_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml', 'image/webp'];
            const MODEL_MAX_SIZE = 50 * 1024 * 1024; // 50MB
            const REEL_MAX_SIZE = 200 * 1024 * 1024; // 200MB
            const REEL_EXT = ['.mp4', '.mov', '.ogg', '.qt', '.avi', '.wmv', '.flv', '.webm'];

            const hasPhotos = window.photosDropzone && window.photosDropzone.files.length > 0;
            const hasModel = window.modelDropzone && window.modelDropzone.files.length > 0;

            // Edit: no minimum photo/model required (existing media may already be there)

            if (hasPhotos) {
                const files = window.photosDropzone.files;
                if (files.length > PHOTOS_MAX) {
                    photosErrors.push(`Maximum ${PHOTOS_MAX} fichiers.`);
                }
                files.forEach((f, i) => {
                    const okType = PHOTO_TYPES.some((t) => f.type === t) || /\.(jpe?g|png|gif|svg|webp)$/i.test(f.name);
                    if (!okType) photosErrors.push(`Photo ${i + 1} : type non autorisé (jpeg, png, jpg, gif, svg, webp).`);
                    if (f.size > PHOTO_MAX_SIZE) photosErrors.push(`Photo ${i + 1} : taille max 5 Mo.`);
                });
            }

            if (hasModel) {
                const f = window.modelDropzone.files[0];
                if (f.size > MODEL_MAX_SIZE) modelErrors.push('Taille max 50 Mo.');
                const isZip = f.type === 'application/zip' || f.name.toLowerCase().endsWith('.zip');
                if (!isZip) modelErrors.push('Seul un fichier ZIP est accepté.');
            }

            if (window.reelDropzone && window.reelDropzone.files.length > 0) {
                const f = window.reelDropzone.files[0];
                if (f.size > REEL_MAX_SIZE) {
                    this.errorMessage = 'Reel (vidéo) : taille max 200 Mo.';
                    return false;
                }
                const ext = f.name.toLowerCase().slice(f.name.lastIndexOf('.'));
                if (!REEL_EXT.includes(ext) && !f.type.startsWith('video/')) {
                    this.errorMessage = 'Reel : format vidéo requis (mp4, mov, ogg, etc.).';
                    return false;
                }
            }

            this.photosError = photosErrors.length ? photosErrors.join(' ') : '';
            this.modelError = modelErrors.length ? modelErrors.join(' ') : '';
            return photosErrors.length === 0 && modelErrors.length === 0;
        },
    };
}

if (typeof window !== 'undefined') {
    window.conceptEditForm = conceptEditFormData;
}
