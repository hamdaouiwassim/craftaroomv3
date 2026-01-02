/**
 * Product Form Stepper Logic
 * Handles multi-step form navigation, validation, and submission
 */

export function productFormData() {
    return {
        currentStep: 1,
        totalSteps: 5,
        formData: {},
        
        stepLabels: {
            1: 'Informations',
            2: 'Détails',
            3: 'Médias',
            4: 'Mesures',
            5: 'Vérification'
        },
        
        nextStep() {
            if (this.validateStep(this.currentStep)) {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                    // Reinitialize Select2 when moving to step 2
                    if (this.currentStep === 2 && window.initSelect2) {
                        setTimeout(() => {
                            window.initSelect2();
                        }, 200);
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
                const name = document.getElementById('name')?.value;
                const category = document.getElementById('category_id')?.value;
                if (!name || !category) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    return false;
                }
            }
            if (step === 2) {
                const price = document.getElementById('price')?.value;
                const currency = document.getElementById('currency')?.value;
                const description = document.getElementById('description')?.value;
                
                // Get Select2 values using jQuery if available
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
                
                if (!price || !currency || !description || rooms.length === 0 || metals.length === 0) {
                    alert('Veuillez remplir tous les champs obligatoires, y compris les pièces et métaux');
                    return false;
                }
            }
            if (step === 3) {
                const photos = window.photosDropzone?.files.length || 0;
                const model = window.modelDropzone?.files.length || 0;
                if (photos === 0 || model === 0) {
                    alert('Veuillez télécharger au moins une photo et le modèle 3D');
                    return false;
                }
            }
            return true;
        },
        
        goToStep(step) {
            // Allow going back to any previous step, or forward if validation passes
            if (step < this.currentStep || step === 1) {
                this.currentStep = step;
                // Reinitialize Select2 when moving to step 2
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => {
                        window.initSelect2();
                    }, 200);
                }
            } else if (step === this.currentStep + 1 && this.validateStep(this.currentStep)) {
                this.currentStep = step;
                // Reinitialize Select2 when moving to step 2
                if (step === 2 && window.initSelect2) {
                    setTimeout(() => {
                        window.initSelect2();
                    }, 200);
                }
            }
        },
        
        updateReview() {
            // This will be called when step 5 is shown
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

            // Disable submit button to prevent double submission
            const submitBtn = form.querySelector('button[type="button"]');
            if (submitBtn) {
                const clickAttr = submitBtn.getAttribute('@click') || submitBtn.getAttribute('x-on:click');
                if (clickAttr && clickAttr.includes('submitForm()')) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>Création en cours...';
                }
            }

            try {
                // Step 1: Create product without files
                const formData = new FormData(form);
                
                // Remove file inputs from formData
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

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
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

                // Step 2: Upload photos separately
                if (window.photosDropzone && window.photosDropzone.files.length > 0) {
                    try {
                        const photosFormData = new FormData();
                        window.photosDropzone.files.forEach(file => {
                            photosFormData.append('photos[]', file);
                        });

                        const photosResponse = await fetch(`/admin/products/${productId}/photos`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: photosFormData
                        });

                        // Check if response is JSON
                        const photosContentType = photosResponse.headers.get('content-type');
                        if (!photosContentType || !photosContentType.includes('application/json')) {
                            uploadErrors.push('Erreur lors de l\'upload des photos: Le serveur a retourné une erreur inattendue.');
                        } else {
                            const photosResult = await photosResponse.json();
                            if (!photosResponse.ok || !photosResult.success) {
                                let errorMessage = photosResult.message || 'Erreur lors de l\'upload des photos';
                                if (photosResult.errors) {
                                    const errorList = Object.values(photosResult.errors).flat().join(', ');
                                    errorMessage += ': ' + errorList;
                                }
                                uploadErrors.push(errorMessage);
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload des photos: ' + error.message);
                    }
                }

                // Step 3: Upload 3D model separately
                if (window.modelDropzone && window.modelDropzone.files.length > 0) {
                    try {
                        const modelFormData = new FormData();
                        modelFormData.append('folderModel', window.modelDropzone.files[0]);

                        const modelResponse = await fetch(`/admin/products/${productId}/model`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: modelFormData
                        });

                        // Check if response is JSON
                        const modelContentType = modelResponse.headers.get('content-type');
                        if (!modelContentType || !modelContentType.includes('application/json')) {
                            uploadErrors.push('Erreur lors de l\'upload du modèle 3D: Le serveur a retourné une erreur inattendue.');
                        } else {
                            const modelResult = await modelResponse.json();
                            if (!modelResponse.ok || !modelResult.success) {
                                let errorMessage = modelResult.message || 'Erreur lors de l\'upload du modèle 3D';
                                if (modelResult.errors) {
                                    const errorList = Object.values(modelResult.errors).flat().join(', ');
                                    errorMessage += ': ' + errorList;
                                }
                                uploadErrors.push(errorMessage);
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload du modèle 3D: ' + error.message);
                    }
                }

                // Step 4: Upload reel separately
                if (window.reelDropzone && window.reelDropzone.files.length > 0) {
                    try {
                        const reelFormData = new FormData();
                        reelFormData.append('reel', window.reelDropzone.files[0]);

                        const reelResponse = await fetch(`/admin/products/${productId}/reel`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: reelFormData
                        });

                        // Check if response is JSON
                        const contentType = reelResponse.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await reelResponse.text();
                            console.error('Non-JSON response from reel upload:', text);
                            uploadErrors.push('Erreur lors de l\'upload du reel: Le serveur a retourné une erreur inattendue.');
                        } else {
                            const reelResult = await reelResponse.json();
                            console.log('Reel upload response:', reelResult);
                            
                            if (!reelResponse.ok || !reelResult.success) {
                                let errorMessage = reelResult.message || 'Erreur lors de l\'upload du reel';
                                if (reelResult.errors) {
                                    const errorList = Object.values(reelResult.errors).flat().join(', ');
                                    errorMessage += ': ' + errorList;
                                }
                                // Add debug info if available
                                if (reelResult.debug) {
                                    console.error('Reel upload debug info:', reelResult.debug);
                                    errorMessage += ' (Vérifiez la console pour plus de détails)';
                                }
                                uploadErrors.push(errorMessage);
                            }
                        }
                    } catch (error) {
                        uploadErrors.push('Erreur lors de l\'upload du reel: ' + error.message);
                    }
                }

                // Redirect on success
                if (uploadErrors.length === 0) {
                    window.location.href = '/admin/products?success=Product created successfully';
                } else {
                    alert('Produit créé mais certaines erreurs sont survenues:\n' + uploadErrors.join('\n'));
                    window.location.href = '/admin/products';
                }
            } catch (error) {
                console.error('Error:', error.message);
                alert('Erreur lors de la création du produit: ' + error.message);
                if (submitBtn) {
                    const clickAttr = submitBtn.getAttribute('@click') || submitBtn.getAttribute('x-on:click');
                    if (clickAttr && clickAttr.includes('submitForm()')) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Créer le produit';
                    }
                }
            }
        }
    };
}

// Make productForm available globally for Alpine.js
window.productForm = productFormData;

