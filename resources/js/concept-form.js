/**
 * Concept Form Stepper Logic
 * Multi-step form for designer/admin concepts (no price/currency).
 * Create and Edit: 5 steps (Informations, Détails, Médias, Mesures, Vérification).
 */

export function conceptFormData() {
    const totalSteps = 5;
    const stepLabels = { 1: 'Informations', 2: 'Détails', 3: 'Médias', 4: 'Mesures', 5: 'Vérification' };

    return {
        currentStep: 1,
        totalSteps,
        stepLabels,
        errorMessage: '',
        uploadErrorRedirectUrl: '',
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
                    if (this.currentStep === 2 && window.initSelect2) setTimeout(() => window.initSelect2(), 200);
                    if (this.currentStep === 5 && typeof this.updateReview === 'function') this.updateReview();
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
                const rooms = roomsSelect ? Array.from(roomsSelect.selectedOptions).map(o => o.value) : [];
                const metals = metalsSelect ? Array.from(metalsSelect.selectedOptions).map(o => o.value) : [];
                if (!description || rooms.length === 0 || metals.length === 0) {
                    this.errorMessage = 'Veuillez remplir la description et sélectionner au moins une pièce et un métal.';
                    return false;
                }
            }
            if (step === 3 && this.totalSteps === 5) {
                this.photosError = '';
                this.modelError = '';
                if (!this.validateMedia()) {
                    return false;
                }
            }
            if (step === 4 && this.totalSteps === 5) {
                const measureSize = document.getElementById('measure_size')?.value;
                const length = document.getElementById('length')?.value;
                const width = document.getElementById('width')?.value;
                const height = document.getElementById('height')?.value;
                const weightValue = document.getElementById('weight_value')?.value;
                const weightUnit = document.getElementById('weight_unit')?.value;
                const hasSize = !!measureSize;
                const hasDimensions = length && width && height;
                const hasWeight = weightValue && weightUnit;
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
                if (step === 2 && window.initSelect2) setTimeout(() => window.initSelect2(), 200);
                if (step === 5 && typeof this.updateReview === 'function') this.updateReview();
            } else if (step === this.currentStep + 1 && this.validateStep(this.currentStep)) {
                this.currentStep = step;
                if (step === 2 && window.initSelect2) setTimeout(() => window.initSelect2(), 200);
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
                if (this.totalSteps === 5) {
                    const photosEl = document.getElementById('review-photos');
                    const modelEl = document.getElementById('review-model');
                    if (photosEl) photosEl.textContent = (window.photosDropzone?.files?.length || 0) + ' photo(s)';
                    if (modelEl) modelEl.textContent = (window.modelDropzone?.files?.length || 0) > 0 ? 'Oui' : 'Non';
                }
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
            const isEdit = typeof document !== 'undefined' && document.getElementById('concept-form')?.querySelector('input[name="_method"][value="PUT"]');

            if (!isEdit) {
                if (!hasPhotos) photosErrors.push('Veuillez ajouter au moins une photo.');
                if (!hasModel) modelErrors.push('Veuillez ajouter un modèle 3D.');
            }

            if (hasPhotos) {
                const files = window.photosDropzone.files;
                if (files.length > PHOTOS_MAX) {
                    photosErrors.push(`Maximum ${PHOTOS_MAX} fichiers.`);
                }
                files.forEach((f, i) => {
                    const okType = PHOTO_TYPES.some(t => f.type === t) || /\.(jpe?g|png|gif|svg|webp)$/i.test(f.name);
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
        async submitForm() {
            const form = document.getElementById('concept-form');
            if (!form || form.querySelector('input[name="_method"][value="PUT"]')) return;

            const submitBtn = form.querySelector('button[type="button"]');
            if (submitBtn && (submitBtn.getAttribute('@click') || submitBtn.getAttribute('x-on:click') || '').includes('submitForm()')) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="animate-pulse">Création en cours...</span>';
            }

            this.photosError = '';
            this.modelError = '';
            if (!this.validateMedia()) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Créer le concept';
                }
                return;
            }
            this.errorMessage = '';

            const routePrefix = form.dataset.routePrefix || 'designer';
            const conceptPath = form.dataset.conceptPath || 'concepts';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const formData = new FormData(form);
                formData.delete('photos[]');
                formData.delete('folderModel');
                formData.delete('reel');

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: formData
                });

                if (response.redirected && response.url) {
                    window.location.href = response.url;
                    return;
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    if (response.ok && response.url && response.url.includes('/customize')) {
                        window.location.href = response.url;
                        return;
                    }
                    throw new Error('Le serveur a retourné une erreur. Vérifiez les champs.');
                }
                const result = await response.json();
                if (!response.ok || !result.success) {
                    let errMsg = result.message;
                    if (result.errors && typeof result.errors === 'object') {
                        const parts = Object.values(result.errors).flat().filter(Boolean);
                        if (parts.length) errMsg = parts.join(' ');
                    }
                    if (!errMsg) errMsg = 'Erreur lors de la création du concept';
                    throw new Error(errMsg);
                }

                const conceptId = result.concept_id;
                if (!conceptId) {
                    throw new Error('Réponse invalide: identifiant du concept manquant.');
                }
                const uploadErrors = [];

                if (window.photosDropzone && window.photosDropzone.files.length > 0) {
                    try {
                        const fd = new FormData();
                        window.photosDropzone.files.forEach(f => fd.append('photos[]', f));
                        const r = await fetch(`/${routePrefix}/${conceptPath}/${conceptId}/photos`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: fd
                        });
                        if (!r.ok) uploadErrors.push('Erreur upload photos');
                    } catch (e) {
                        uploadErrors.push('Erreur upload photos: ' + e.message);
                    }
                }

                if (window.modelDropzone && window.modelDropzone.files.length > 0) {
                    try {
                        const fd = new FormData();
                        fd.append('folderModel', window.modelDropzone.files[0]);
                        const r = await fetch(`/${routePrefix}/${conceptPath}/${conceptId}/model`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: fd
                        });
                        if (!r.ok) uploadErrors.push('Erreur upload modèle 3D');
                    } catch (e) {
                        uploadErrors.push('Erreur upload modèle 3D: ' + e.message);
                    }
                }

                if (window.reelDropzone && window.reelDropzone.files.length > 0) {
                    try {
                        const fd = new FormData();
                        fd.append('reel', window.reelDropzone.files[0]);
                        const r = await fetch(`/${routePrefix}/${conceptPath}/${conceptId}/reel`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: fd
                        });
                        if (!r.ok) uploadErrors.push('Erreur upload reel');
                    } catch (e) {
                        uploadErrors.push('Erreur upload reel: ' + e.message);
                    }
                }

                const redirectUrl = result.redirect_url || `/${routePrefix}/concepts/${conceptId}/customize`;
                if (uploadErrors.length === 0) {
                    window.location.href = redirectUrl;
                } else {
                    this.errorMessage = 'Concept créé mais erreurs upload : ' + uploadErrors.join(' ') + '. Vous pouvez continuer vers la personnalisation.';
                    this.uploadErrorRedirectUrl = redirectUrl;
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Créer le concept';
                    }
                }
            } catch (err) {
                console.error(err);
                this.errorMessage = 'Erreur : ' + err.message;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Créer le concept';
                }
            }
        }
    };
}

if (typeof window !== 'undefined') {
    window.conceptForm = conceptFormData;
}
