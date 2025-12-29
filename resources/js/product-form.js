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
                const currency = document.getElementById('currency')?.value;
                if (!name || !category || !currency) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    return false;
                }
            }
            if (step === 2) {
                const price = document.getElementById('price')?.value;
                const size = document.getElementById('size')?.value;
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
                
                if (!price || !size || !description || rooms.length === 0 || metals.length === 0) {
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
        
        submitForm() {
            const form = document.getElementById('product-form');
            if (!form) {
                console.error('Product form not found');
                return;
            }
            
            // Create hidden file inputs for Dropzone files
            // Remove existing hidden inputs first
            document.querySelectorAll('input[type="file"][name^="dropzone_"]').forEach(el => el.remove());
            
            // Add photos
            if (window.photosDropzone && window.photosDropzone.files.length > 0) {
                window.photosDropzone.files.forEach((file, index) => {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.name = 'photos[]';
                    input.id = `photo-input-${index}`;
                    input.style.display = 'none';
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    form.appendChild(input);
                });
            }
            
            // Add 3D model
            if (window.modelDropzone && window.modelDropzone.files.length > 0) {
                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'folderModel';
                input.id = 'model-input';
                input.style.display = 'none';
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(window.modelDropzone.files[0]);
                input.files = dataTransfer.files;
                form.appendChild(input);
            }
            
            // Add reel
            if (window.reelDropzone && window.reelDropzone.files.length > 0) {
                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'reel';
                input.id = 'reel-input';
                input.style.display = 'none';
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(window.reelDropzone.files[0]);
                input.files = dataTransfer.files;
                form.appendChild(input);
            }
            
            // Submit form normally
            form.submit();
        }
    };
}

// Make productForm available globally for Alpine.js
window.productForm = productFormData;

