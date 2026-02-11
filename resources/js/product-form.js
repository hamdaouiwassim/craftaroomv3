/**
 * Product Form Stepper Logic - Normal Products (No Concept)
 * Strict validation requiring all uploads
 */
export function productFormData() {
    return {
        currentStep: 1,
        totalSteps: 5,
        formData: {},
        mediaValidationState: {
            photos: false,
            model: false
        },
        
        stepLabels: {
            1: 'Informations',
            2: 'Détails',
            3: 'Médias',
            4: 'Mesures',
            5: 'Vérification'
        },
        
        init() {
            // Set up Dropzone event listeners for real-time validation
            this.$nextTick(() => {
                this.setupDropzoneListeners();
            });
        },
        
        setupDropzoneListeners() {
            // Listen to photos dropzone
            if (window.photosDropzone) {
                window.photosDropzone.on('addedfile', () => {
                    this.checkMediaValidation();
                });
                window.photosDropzone.on('removedfile', () => {
                    this.checkMediaValidation();
                });
            }
            
            // Listen to model dropzone
            if (window.modelDropzone) {
                window.modelDropzone.on('addedfile', () => {
                    this.checkMediaValidation();
                });
                window.modelDropzone.on('removedfile', () => {
                    this.checkMediaValidation();
                });
            }
            
            // Initial validation check
            this.checkMediaValidation();
        },
        
        checkMediaValidation() {
            // Silent validation check without alerts
            const uploadedPhotos = window.photosDropzone?.files.length || 0;
            const uploadedModel = window.modelDropzone?.files.length || 0;
            
            this.mediaValidationState.photos = uploadedPhotos > 0;
            this.mediaValidationState.model = uploadedModel > 0;
            
            console.log('Media validation state updated:', this.mediaValidationState);
        },
        
        nextStep() {
            if (this.validateStep(this.currentStep)) {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                    if (this.currentStep === 2 && window.initSelect2) {
                        setTimeout(() => window.initSelect2(), 200);
                    }
                }
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        validateStep(step) {
            if (step === 1) {
                const name = document.getElementById('name')?.value?.trim();
                const category = document.getElementById('category_id')?.value;
                
                if (!name) {
                    alert('❌ Erreur - Étape 1 (Informations): Le nom du produit est obligatoire');
                    return false;
                }
                
                if (!category) {
                    alert('❌ Erreur - Étape 1 (Informations): La catégorie est obligatoire');
                    return false;
                }
            }
            
            if (step === 2) {
                const price = document.getElementById('price')?.value?.trim();
                const currency = document.getElementById('currency')?.value;
                const description = document.getElementById('description')?.value?.trim();
                
                let rooms = [];
                let metals = [];
                if (window.$ && $('#rooms').length) {
                    rooms = $('#rooms').val() || [];
                } else {
                    const roomsSelect = document.getElementById('rooms');
                    if (roomsSelect) {
                        rooms = Array.from(roomsSelect.selectedOptions).map(opt => opt.value);
                    }
                }
                
                if (window.$ && $('#metals').length) {
                    metals = $('#metals').val() || [];
                } else {
                    const metalsSelect = document.getElementById('metals');
                    if (metalsSelect) {
                        metals = Array.from(metalsSelect.selectedOptions).map(opt => opt.value);
                    }
                }
                
                if (!price) {
                    alert('❌ Erreur - Étape 2 (Détails): Le prix est obligatoire');
                    return false;
                }
                
                if (!currency) {
                    alert('❌ Erreur - Étape 2 (Détails): La devise est obligatoire');
                    return false;
                }
                
                if (!description) {
                    alert('❌ Erreur - Étape 2 (Détails): La description est obligatoire');
                    return false;
                }
                
                if (rooms.length === 0) {
                    alert('❌ Erreur - Étape 2 (Détails): Veuillez sélectionner au moins une pièce');
                    return false;
                }
                
                if (metals.length === 0) {
                    alert('❌ Erreur - Étape 2 (Détails): Veuillez sélectionner au moins un métal');
                    return false;
                }
            }
            
            if (step === 3) {
                // NORMAL PRODUCT: Require both photos AND 3D model uploads
                let uploadedPhotos = 0;
                let uploadedModel = 0;
                
                if (window.photosDropzone && window.photosDropzone.files) {
                    uploadedPhotos = window.photosDropzone.files.length;
                }
                
                if (window.modelDropzone && window.modelDropzone.files) {
                    uploadedModel = window.modelDropzone.files.length;
                }
                
                console.log('=== Validation Étape 3 (Médias) - Mode NORMAL ===');
                console.log('Uploaded Photos:', uploadedPhotos);
                console.log('Uploaded Model:', uploadedModel);
                
                if (uploadedPhotos === 0) {
                    alert('❌ Erreur - Étape 3 (Médias): Veuillez télécharger au moins une photo du produit.\n\nGlissez-déposez vos photos dans la zone "Photos du produit"');
                    return false;
                }
                
                if (uploadedModel === 0) {
                    alert('❌ Erreur - Étape 3 (Médias): Le modèle 3D est OBLIGATOIRE.\n\nVeuillez télécharger un fichier ZIP contenant le modèle 3D');
                    return false;
                }
            }
            
            if (step === 4) {
                const length = document.getElementById('length')?.value;
                const height = document.getElementById('height')?.value;
                const width = document.getElementById('width')?.value;
                const measureSize = document.getElementById('measure_size')?.value;
                const weightValue = document.getElementById('weight_value')?.value;
                
                const hasDimensions = length && height && width;
                const hasSize = measureSize && measureSize !== '';
                const hasWeight = weightValue && weightValue !== '';
                
                if (!hasDimensions && !hasSize && !hasWeight) {
                    alert('❌ Erreur - Étape 4 (Mesures): Veuillez renseigner au moins une mesure (dimensions, taille ou poids)');
                    return false;
                }
                
                if ((length || height || width) && !hasDimensions) {
                    alert('❌ Erreur - Étape 4 (Mesures): Si vous renseignez les dimensions, veuillez remplir Longueur, Hauteur et Largeur');
                    return false;
                }
            }
            
            return true;
        },
        
        goToStep(step) {
            if (step < this.currentStep || step === 1) {
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
                return;
            }
            
            if (step > this.currentStep) {
                for (let i = this.currentStep; i < step; i++) {
                    if (!this.validateStep(i)) {
                        console.log(`Validation failed at step ${i}`);
                        return;
                    }
                }
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
            }
        },
        
        updateReview() {
            setTimeout(() => {
                const nameEl = document.getElementById('review-name');
                const categoryEl = document.getElementById('review-category');
                const priceEl = document.getElementById('review-price');
                const photosEl = document.getElementById('review-photos');
                const modelEl = document.getElementById('review-model');
                
                if (nameEl) {
                    const nameInput = document.getElementById('name');
                    nameEl.textContent = nameInput ? nameInput.value : '-';
                }
                
                if (categoryEl) {
                    const categorySelect = document.getElementById('category_id');
                    if (categorySelect && categorySelect.selectedIndex >= 0) {
                        categoryEl.textContent = categorySelect.options[categorySelect.selectedIndex].text;
                    } else {
                        categoryEl.textContent = '-';
                    }
                }
                
                if (priceEl) {
                    const priceInput = document.getElementById('price');
                    priceEl.textContent = priceInput ? priceInput.value : '-';
                }
                
                if (photosEl) {
                    photosEl.textContent = (window.photosDropzone?.files.length || 0) + ' photo(s)';
                }
                
                if (modelEl) {
                    modelEl.textContent = (window.modelDropzone?.files.length || 0) > 0 ? 'Oui' : 'Non';
                }
            }, 100);
        },
        
        async submitForm() {
            const form = document.getElementById('product-form');
            if (!form) {
                console.error('Product form not found');
                return;
            }

            const submitBtn = form.querySelector('button[type="button"]');
            if (submitBtn) {
                const clickAttr = submitBtn.getAttribute('@click') || submitBtn.getAttribute('x-on:click');
                if (clickAttr && clickAttr.includes('submitForm()')) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>Création en cours...';
                }
            }

            try {
                const routePrefix = form.dataset.routePrefix || 'admin';
                const formData = new FormData(form);
                
                formData.delete('photos[]');
                formData.delete('folderModel');
                formData.delete('reel');

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Le serveur a retourné une erreur. Veuillez vérifier les champs du formulaire.');
                }

                const result = await response.json();

                if (!response.ok || !result.success) {
                    let errorMessage = result.message || 'Erreur lors de la création du produit';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMessage += ': ' + errorList;
                    }
                    throw new Error(errorMessage);
                }

                const productId = result.product_id;
                let uploadErrors = [];

                // Upload photos
                if (window.photosDropzone && window.photosDropzone.files.length > 0) {
                    try {
                        const photosFormData = new FormData();
                        window.photosDropzone.files.forEach(file => {
                            photosFormData.append('photos[]', file);
                        });

                        const photosResponse = await fetch(`/${routePrefix}/products/${productId}/photos`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: photosFormData
                        });

                        const photosContentType = photosResponse.headers.get('content-type');
                        if (!photosContentType || !photosContentType.includes('application/json')) {
                            uploadErrors.push('Erreur lors de l\'upload des photos');
                        } else {
                            const photosResult = await photosResponse.json();
                            if (!photosResponse.ok || !photosResult.success) {
                                uploadErrors.push(photosResult.message || 'Erreur lors de l\'upload des photos');
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload des photos: ' + error.message);
                    }
                }

                // Upload 3D model
                if (window.modelDropzone && window.modelDropzone.files.length > 0) {
                    try {
                        const modelFormData = new FormData();
                        modelFormData.append('folderModel', window.modelDropzone.files[0]);

                        const modelResponse = await fetch(`/${routePrefix}/products/${productId}/model`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: modelFormData
                        });

                        const modelContentType = modelResponse.headers.get('content-type');
                        if (!modelContentType || !modelContentType.includes('application/json')) {
                            uploadErrors.push('Erreur lors de l\'upload du modèle 3D');
                        } else {
                            const modelResult = await modelResponse.json();
                            if (!modelResponse.ok || !modelResult.success) {
                                uploadErrors.push(modelResult.message || 'Erreur lors de l\'upload du modèle 3D');
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload du modèle 3D: ' + error.message);
                    }
                }

                // Upload reel
                if (window.reelDropzone && window.reelDropzone.files.length > 0) {
                    try {
                        const reelFormData = new FormData();
                        reelFormData.append('reel', window.reelDropzone.files[0]);

                        const reelResponse = await fetch(`/${routePrefix}/products/${productId}/reel`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: reelFormData
                        });

                        const contentType = reelResponse.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const reelResult = await reelResponse.json();
                            if (!reelResponse.ok || !reelResult.success) {
                                uploadErrors.push(reelResult.message || 'Erreur lors de l\'upload du reel');
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload du reel: ' + error.message);
                    }
                }

                if (uploadErrors.length === 0) {
                    window.location.href = `/${routePrefix}/products?success=Product created successfully`;
                } else {
                    alert('Produit créé mais certaines erreurs sont survenues:\n' + uploadErrors.join('\n'));
                    window.location.href = `/${routePrefix}/products`;
                }
            } catch (error) {
                console.error('Error:', error.message);
                alert('Erreur lors de la création du produit: ' + error.message);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Créer le produit';
                }
            }
        },
        
        goToStep(step) {
            if (step < this.currentStep || step === 1) {
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
                return;
            }
            
            if (step > this.currentStep) {
                for (let i = this.currentStep; i < step; i++) {
                    if (!this.validateStep(i)) {
                        console.log(`Validation failed at step ${i}`);
                        return;
                    }
                }
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
            }
        },
        
        updateReview() {
            setTimeout(() => {
                const nameEl = document.getElementById('review-name');
                const categoryEl = document.getElementById('review-category');
                const priceEl = document.getElementById('review-price');
                const photosEl = document.getElementById('review-photos');
                const modelEl = document.getElementById('review-model');
                
                if (nameEl) {
                    const nameInput = document.getElementById('name');
                    nameEl.textContent = nameInput ? nameInput.value : '-';
                }
                
                if (categoryEl) {
                    const categorySelect = document.getElementById('category_id');
                    if (categorySelect && categorySelect.selectedIndex >= 0) {
                        categoryEl.textContent = categorySelect.options[categorySelect.selectedIndex].text;
                    } else {
                        categoryEl.textContent = '-';
                    }
                }
                
                if (priceEl) {
                    const priceInput = document.getElementById('price');
                    priceEl.textContent = priceInput ? priceInput.value : '-';
                }
                
                if (photosEl) {
                    photosEl.textContent = (window.photosDropzone?.files.length || 0) + ' photo(s)';
                }
                
                if (modelEl) {
                    modelEl.textContent = (window.modelDropzone?.files.length || 0) > 0 ? 'Oui' : 'Non';
                }
            }, 100);
        }
    };
}

/**
 * Product Form Stepper Logic - From Concept
 * Flexible validation allowing concept media to be used
 */
export function productFormWithConceptData(conceptPhotos = [], hasConceptReel = false) {
    return {
        currentStep: 1,
        totalSteps: 5,
        formData: {},
        conceptPhotosToDelete: [],
        conceptReelDeleted: false,
        mediaValidationState: {
            photos: false,
            model: false
        },
        
        stepLabels: {
            1: 'Informations',
            2: 'Détails',
            3: 'Médias',
            4: 'Mesures',
            5: 'Vérification'
        },
        
        init() {
            // Set up Dropzone event listeners and concept photo deletion for real-time validation
            this.$nextTick(() => {
                this.setupDropzoneListeners();
                this.checkMediaValidation(); // Initial check
            });
        },
        
        setupDropzoneListeners() {
            // Listen to photos dropzone
            if (window.photosDropzone) {
                window.photosDropzone.on('addedfile', () => {
                    this.checkMediaValidation();
                });
                window.photosDropzone.on('removedfile', () => {
                    this.checkMediaValidation();
                });
            }
            
            // Listen to model dropzone
            if (window.modelDropzone) {
                window.modelDropzone.on('addedfile', () => {
                    this.checkMediaValidation();
                });
                window.modelDropzone.on('removedfile', () => {
                    this.checkMediaValidation();
                });
            }
            
            // Listen to reel dropzone
            if (window.reelDropzone) {
                window.reelDropzone.on('addedfile', () => {
                    this.checkMediaValidation();
                });
                window.reelDropzone.on('removedfile', () => {
                    this.checkMediaValidation();
                });
            }
            
            // Initial validation check
            this.checkMediaValidation();
        },
        
        checkMediaValidation() {
            // Silent validation check without alerts for concept-based products
            const uploadedPhotos = window.photosDropzone?.files.length || 0;
            const uploadedModel = window.modelDropzone?.files.length || 0;
            const conceptPhotosInputs = document.querySelectorAll('input[name^="concept_photos"]');
            const remainingConceptPhotos = conceptPhotosInputs.length;
            const hasConceptModel = document.querySelector('input[name="concept_3d_model"]')?.value?.trim() !== '';
            
            this.mediaValidationState.photos = (remainingConceptPhotos > 0) || (uploadedPhotos > 0);
            this.mediaValidationState.model = hasConceptModel || (uploadedModel > 0);
            
            console.log('Media validation state updated (concept mode):', {
                photos: this.mediaValidationState.photos,
                model: this.mediaValidationState.model,
                remainingConceptPhotos,
                uploadedPhotos,
                hasConceptModel,
                uploadedModel
            });
        },
        
        removeConceptPhoto(photoId) {
            if (!this.conceptPhotosToDelete.includes(photoId)) {
                this.conceptPhotosToDelete.push(photoId);
            }
            // Re-check validation after removing concept photo
            this.$nextTick(() => {
                this.checkMediaValidation();
            });
        },
        
        removeConceptReel() {
            this.conceptReelDeleted = true;
            // Re-check validation after removing concept reel
            this.$nextTick(() => {
                this.checkMediaValidation();
            });
        },
        
        nextStep() {
            if (this.validateStep(this.currentStep)) {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                    if (this.currentStep === 2 && window.initSelect2) {
                        setTimeout(() => window.initSelect2(), 200);
                    }
                }
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        validateStep(step) {
            if (step === 1) {
                const name = document.getElementById('name')?.value?.trim();
                const category = document.getElementById('category_id')?.value;
                
                if (!name) {
                    alert('❌ Erreur - Étape 1 (Informations): Le nom du produit est obligatoire');
                    return false;
                }
                
                if (!category) {
                    alert('❌ Erreur - Étape 1 (Informations): La catégorie est obligatoire');
                    return false;
                }
            }
            
            if (step === 2) {
                const price = document.getElementById('price')?.value?.trim();
                const currency = document.getElementById('currency')?.value;
                const description = document.getElementById('description')?.value?.trim();
                
                let rooms = [];
                let metals = [];
                if (window.$ && $('#rooms').length) {
                    rooms = $('#rooms').val() || [];
                } else {
                    const roomsSelect = document.getElementById('rooms');
                    if (roomsSelect) {
                        rooms = Array.from(roomsSelect.selectedOptions).map(opt => opt.value);
                    }
                }
                
                if (window.$ && $('#metals').length) {
                    metals = $('#metals').val() || [];
                } else {
                    const metalsSelect = document.getElementById('metals');
                    if (metalsSelect) {
                        metals = Array.from(metalsSelect.selectedOptions).map(opt => opt.value);
                    }
                }
                
                if (!price) {
                    alert('❌ Erreur - Étape 2 (Détails): Le prix est obligatoire');
                    return false;
                }
                
                if (!currency) {
                    alert('❌ Erreur - Étape 2 (Détails): La devise est obligatoire');
                    return false;
                }
                
                if (!description) {
                    alert('❌ Erreur - Étape 2 (Détails): La description est obligatoire');
                    return false;
                }
                
                if (rooms.length === 0) {
                    alert('❌ Erreur - Étape 2 (Détails): Veuillez sélectionner au moins une pièce');
                    return false;
                }
                
                if (metals.length === 0) {
                    alert('❌ Erreur - Étape 2 (Détails): Veuillez sélectionner au moins un métal');
                    return false;
                }
            }
            
            if (step === 3) {
                // CONCEPT-BASED PRODUCT: Allow concept media OR uploads
                let uploadedPhotos = 0;
                let uploadedModel = 0;
                
                if (window.photosDropzone && window.photosDropzone.files) {
                    uploadedPhotos = window.photosDropzone.files.length;
                }
                
                if (window.modelDropzone && window.modelDropzone.files) {
                    uploadedModel = window.modelDropzone.files.length;
                }
                
                const conceptPhotosInputs = document.querySelectorAll('input[name^="concept_photos"]');
                const remainingConceptPhotos = conceptPhotosInputs.length;
                const hasConceptModel = document.querySelector('input[name="concept_3d_model"]')?.value?.trim() !== '';
                
                console.log('=== Validation Étape 3 (Médias) - Mode CONCEPT ===');
                console.log('Remaining Concept Photos:', remainingConceptPhotos);
                console.log('Uploaded Photos:', uploadedPhotos);
                console.log('Has Concept Model:', hasConceptModel);
                console.log('Uploaded Model:', uploadedModel);
                
                // Validate photos
                if (remainingConceptPhotos === 0 && uploadedPhotos === 0) {
                    alert('❌ Erreur - Étape 3 (Médias): Veuillez conserver au moins une photo du concept ou télécharger de nouvelles photos');
                    return false;
                }
                
                // Validate 3D model
                if (!hasConceptModel && uploadedModel === 0) {
                    alert('❌ Erreur - Étape 3 (Médias): Veuillez conserver le modèle 3D du concept ou télécharger un nouveau modèle 3D');
                    return false;
                }
            }
            
            if (step === 4) {
                const length = document.getElementById('length')?.value;
                const height = document.getElementById('height')?.value;
                const width = document.getElementById('width')?.value;
                const measureSize = document.getElementById('measure_size')?.value;
                const weightValue = document.getElementById('weight_value')?.value;
                
                const hasDimensions = length && height && width;
                const hasSize = measureSize && measureSize !== '';
                const hasWeight = weightValue && weightValue !== '';
                
                if (!hasDimensions && !hasSize && !hasWeight) {
                    alert('❌ Erreur - Étape 4 (Mesures): Veuillez renseigner au moins une mesure (dimensions, taille ou poids)');
                    return false;
                }
                
                if ((length || height || width) && !hasDimensions) {
                    alert('❌ Erreur - Étape 4 (Mesures): Si vous renseignez les dimensions, veuillez remplir Longueur, Hauteur et Largeur');
                    return false;
                }
            }
            
            return true;
        },
        
        goToStep(step) {
            if (step < this.currentStep || step === 1) {
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
                return;
            }
            
            if (step > this.currentStep) {
                for (let i = this.currentStep; i < step; i++) {
                    if (!this.validateStep(i)) {
                        console.log(`Validation failed at step ${i}`);
                        return;
                    }
                }
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
            }
        },
        
        updateReview() {
            setTimeout(() => {
                const nameEl = document.getElementById('review-name');
                const categoryEl = document.getElementById('review-category');
                const priceEl = document.getElementById('review-price');
                const photosEl = document.getElementById('review-photos');
                const modelEl = document.getElementById('review-model');
                
                if (nameEl) {
                    const nameInput = document.getElementById('name');
                    nameEl.textContent = nameInput ? nameInput.value : '-';
                }
                
                if (categoryEl) {
                    const categorySelect = document.getElementById('category_id');
                    if (categorySelect && categorySelect.selectedIndex >= 0) {
                        categoryEl.textContent = categorySelect.options[categorySelect.selectedIndex].text;
                    } else {
                        categoryEl.textContent = '-';
                    }
                }
                
                if (priceEl) {
                    const priceInput = document.getElementById('price');
                    priceEl.textContent = priceInput ? priceInput.value : '-';
                }
                
                if (photosEl) {
                    const totalPhotos = (window.photosDropzone?.files.length || 0);
                    const conceptPhotosCount = document.querySelectorAll('input[name^="concept_photos"]').length;
                    photosEl.textContent = (totalPhotos + conceptPhotosCount) + ' photo(s)';
                }
                
                if (modelEl) {
                    const hasUploadedModel = (window.modelDropzone?.files.length || 0) > 0;
                    const hasConceptModel = document.querySelector('input[name="concept_3d_model"]')?.value?.trim() !== '';
                    modelEl.textContent = (hasUploadedModel || hasConceptModel) ? 'Oui' : 'Non';
                }
            }, 100);
        },
        
        async submitForm() {
            const form = document.getElementById('product-form');
            if (!form) {
                console.error('Product form not found');
                return;
            }

            const submitBtn = form.querySelector('button[type="button"]');
            if (submitBtn) {
                const clickAttr = submitBtn.getAttribute('@click') || submitBtn.getAttribute('x-on:click');
                if (clickAttr && clickAttr.includes('submitForm()')) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>Création en cours...';
                }
            }

            try {
                const routePrefix = form.dataset.routePrefix || 'admin';
                const formData = new FormData(form);
                
                formData.delete('photos[]');
                formData.delete('folderModel');
                formData.delete('reel');

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Le serveur a retourné une erreur. Veuillez vérifier les champs du formulaire.');
                }

                const result = await response.json();

                if (!response.ok || !result.success) {
                    let errorMessage = result.message || 'Erreur lors de la création du produit';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMessage += ': ' + errorList;
                    }
                    throw new Error(errorMessage);
                }

                const productId = result.product_id;
                let uploadErrors = [];

                // Upload photos
                if (window.photosDropzone && window.photosDropzone.files.length > 0) {
                    try {
                        const photosFormData = new FormData();
                        window.photosDropzone.files.forEach(file => {
                            photosFormData.append('photos[]', file);
                        });

                        const photosResponse = await fetch(`/${routePrefix}/products/${productId}/photos`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: photosFormData
                        });

                        const photosContentType = photosResponse.headers.get('content-type');
                        if (!photosContentType || !photosContentType.includes('application/json')) {
                            uploadErrors.push('Erreur lors de l\'upload des photos');
                        } else {
                            const photosResult = await photosResponse.json();
                            if (!photosResponse.ok || !photosResult.success) {
                                uploadErrors.push(photosResult.message || 'Erreur lors de l\'upload des photos');
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload des photos: ' + error.message);
                    }
                }

                // Upload 3D model
                if (window.modelDropzone && window.modelDropzone.files.length > 0) {
                    try {
                        const modelFormData = new FormData();
                        modelFormData.append('folderModel', window.modelDropzone.files[0]);

                        const modelResponse = await fetch(`/${routePrefix}/products/${productId}/model`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: modelFormData
                        });

                        const modelContentType = modelResponse.headers.get('content-type');
                        if (!modelContentType || !modelContentType.includes('application/json')) {
                            uploadErrors.push('Erreur lors de l\'upload du modèle 3D');
                        } else {
                            const modelResult = await modelResponse.json();
                            if (!modelResponse.ok || !modelResult.success) {
                                uploadErrors.push(modelResult.message || 'Erreur lors de l\'upload du modèle 3D');
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload du modèle 3D: ' + error.message);
                    }
                }

                // Upload reel
                if (window.reelDropzone && window.reelDropzone.files.length > 0) {
                    try {
                        const reelFormData = new FormData();
                        reelFormData.append('reel', window.reelDropzone.files[0]);

                        const reelResponse = await fetch(`/${routePrefix}/products/${productId}/reel`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: reelFormData
                        });

                        const contentType = reelResponse.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const reelResult = await reelResponse.json();
                            if (!reelResponse.ok || !reelResult.success) {
                                uploadErrors.push(reelResult.message || 'Erreur lors de l\'upload du reel');
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload du reel: ' + error.message);
                    }
                }

                if (uploadErrors.length === 0) {
                    window.location.href = `/${routePrefix}/products?success=Product created successfully`;
                } else {
                    alert('Produit créé mais certaines erreurs sont survenues:\n' + uploadErrors.join('\n'));
                    window.location.href = `/${routePrefix}/products`;
                }
            } catch (error) {
                console.error('Error:', error.message);
                alert('Erreur lors de la création du produit: ' + error.message);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Créer le produit';
                }
            }
        },
        
        goToStep(step) {
            if (step < this.currentStep || step === 1) {
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
                return;
            }
            
            if (step > this.currentStep) {
                for (let i = this.currentStep; i < step; i++) {
                    if (!this.validateStep(i)) {
                        console.log(`Validation failed at step ${i}`);
                        return;
                    }
                }
                this.currentStep = step;
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => window.initSelect2(), 200);
                }
            }
        },
        
        updateReview() {
            setTimeout(() => {
                const nameEl = document.getElementById('review-name');
                const categoryEl = document.getElementById('review-category');
                const priceEl = document.getElementById('review-price');
                const photosEl = document.getElementById('review-photos');
                const modelEl = document.getElementById('review-model');
                
                if (nameEl) {
                    const nameInput = document.getElementById('name');
                    nameEl.textContent = nameInput ? nameInput.value : '-';
                }
                
                if (categoryEl) {
                    const categorySelect = document.getElementById('category_id');
                    if (categorySelect && categorySelect.selectedIndex >= 0) {
                        categoryEl.textContent = categorySelect.options[categorySelect.selectedIndex].text;
                    } else {
                        categoryEl.textContent = '-';
                    }
                }
                
                if (priceEl) {
                    const priceInput = document.getElementById('price');
                    priceEl.textContent = priceInput ? priceInput.value : '-';
                }
                
                if (photosEl) {
                    const totalPhotos = (window.photosDropzone?.files.length || 0);
                    const conceptPhotosCount = document.querySelectorAll('input[name^="concept_photos"]').length;
                    photosEl.textContent = (totalPhotos + conceptPhotosCount) + ' photo(s)';
                }
                
                if (modelEl) {
                    const hasUploadedModel = (window.modelDropzone?.files.length || 0) > 0;
                    const hasConceptModel = document.querySelector('input[name="concept_3d_model"]')?.value?.trim() !== '';
                    modelEl.textContent = (hasUploadedModel || hasConceptModel) ? 'Oui' : 'Non';
                }
            }, 100);
        }
    };
}

// Make functions available globally for Alpine.js
window.productForm = productFormData;
window.productFormWithConcept = productFormWithConceptData;
