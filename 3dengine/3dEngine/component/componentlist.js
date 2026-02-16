// componentlist.js

import { getComponentsConfig, setComponentsConfig, getComponentCategories } from '../api.js';

window.initComponentList = function () {

    const componentsList = document.getElementById('components-list');
    const componentImageList = document.getElementById('component-image-list');
    const componentsItemsContainer = document.querySelector('.components-items-container');
    const componentsTool = document.getElementById('components-tool');

    // ========== GET CONFIG FROM API ==========
    let componentImagesData = getComponentsConfig();
    
    // Get categories from database (if loaded from API)
    const apiCategories = getComponentCategories();

    // Default config if API empty
    if (!componentImagesData) {
        console.warn('⚠️ No components configured via API. Using default configuration.');
        componentImagesData = {
            wood: [
                { url: 'component/assets/wood/wood1.jpg', name: 'Oak' },
                { url: 'component/assets/wood/wood2.jpeg', name: 'Walnut' },
                { url: 'component/assets/wood/wood3.jpg', name: 'Pine' },
                { url: 'component/assets/wood/wood4.jpg', name: 'Mahogany' }
            ],
            metal: [
                { url: 'component/assets/metal/metal1.jpg', name: 'Steel' },
                { url: 'component/assets/metal/metal2.jpg', name: 'Brass' },
                { url: 'component/assets/metal/metal3.jpg', name: 'Copper' }
            ],
            fabric: [
                { url: 'component/assets/fabric/fabric1.jpg', name: 'Linen' },
                { url: 'component/assets/fabric/fabric2.jpg', name: 'Cotton' },
                { url: 'component/assets/fabric/fabric3.jpg', name: 'Leather' },
                { url: 'component/assets/fabric/fabric4.jpg', name: 'Velvet' }
            ]
        };
        setComponentsConfig(componentImagesData);
    }

    console.log('✅ Components data loaded:', Object.keys(componentImagesData));

    // ========== DEFAULT CATEGORY ICONS (fallback) ==========
    const defaultCategoryIcons = {
        wood: 'https://cdn-icons-png.flaticon.com/512/9410/9410721.png',
        metal: 'https://img.freepik.com/premium-vector/steel-icon-vector-design-templates_1172029-3074.jpg',
        fabric: 'https://images.vexels.com/media/users/3/212432/isolated/preview/964529c7d1e4eb2bd5f0d8cd0e3fdced-fabric-sample-swatch-flat-icon.png',
        paint: 'https://cdn-icons-png.flaticon.com/512/1827/1827951.png',
        glass: 'https://cdn-icons-png.flaticon.com/512/2913/2913133.png',
        leather: 'https://cdn-icons-png.flaticon.com/512/3050/3050158.png'
    };

    // ========== DYNAMICALLY CREATE CATEGORY BUTTONS ==========
    function createCategoryButtons() {
        // Find the buttons container (after tint buttons)
        const tintBtn = document.getElementById('toggle-tint-btn');
        const resetBtn = document.getElementById('reset-textures-btn');
        
        // Remove old category buttons if they exist
        const oldButtons = componentsItemsContainer.querySelectorAll('.component-item');
        oldButtons.forEach(btn => btn.remove());

        // Create buttons for each category in config
        Object.keys(componentImagesData).forEach(categoryKey => {
            const div = document.createElement('div');
            div.className = 'tool-item component-item';
            div.setAttribute('data-type', categoryKey);
            
            const img = document.createElement('img');
            // Use icon from database if available, otherwise use default
            const categoryInfo = apiCategories?.[categoryKey];
            const iconUrl = categoryInfo?.icon || defaultCategoryIcons[categoryKey] || 'https://cdn-icons-png.flaticon.com/512/1827/1827951.png';
            img.src = iconUrl;
            img.className = 'component-icon';
            
            const label = document.createElement('div');
            label.className = 'component-tool-label';
            // Use name from database if available, otherwise format the key
            const displayName = categoryInfo?.name || 
                               (categoryKey.charAt(0).toUpperCase() + categoryKey.slice(1));
            label.textContent = displayName;
            
            div.appendChild(img);
            div.appendChild(label);
            componentsItemsContainer.appendChild(div);
            
            // Add click listener
            div.addEventListener('click', () => {
                // Remove selected from all
                componentsItemsContainer.querySelectorAll('.component-item').forEach(i => i.classList.remove('selected'));
                // Add selected to clicked
                div.classList.add('selected');
                // Show images
                showComponentImages(categoryKey);
            });
        });
    }

    // ========== SHOW IMAGES ==========
    function showComponentImages(type) {
        componentImageList.innerHTML = '';

        if (!componentImagesData[type]) {
            componentImageList.style.display = 'none';
            return;
        }

        componentImageList.style.display = 'flex';

        componentImagesData[type].forEach(data => {
            const img = document.createElement('img');
            img.src = data.url;
            img.title = data.name;

            img.addEventListener('click', () => {
                // Check if part selected
                if (!window.selectedMaterialName) {
                    window.customAlert("Please double-click a part of the object first!");
                    return;
                }

                // Remove selected from all images
                const allImages = componentImageList.querySelectorAll('img');
                allImages.forEach(i => i.classList.remove('selected'));
                
                // Add selected to clicked image
                img.classList.add('selected');

                // Apply texture
                if (typeof window.applyTextureToSelected === 'function') {
                    window.applyTextureToSelected(data.url);
                }

                // Show buttons
                const tintToggleBtn = document.getElementById('toggle-tint-btn');
                const resetBtn = document.getElementById('reset-textures-btn');
                if (tintToggleBtn) tintToggleBtn.style.display = 'flex';
                if (resetBtn) resetBtn.style.display = 'flex';
                
                // Show global reset
                window.showGlobalReset();
            });

            componentImageList.appendChild(img);
        });
    }

    // ========== TINT/COLOR CONTROLS ==========
    const tintToggleBtn = document.getElementById('toggle-tint-btn');
    const tintPanel = document.getElementById('tint-sliders-panel');
    const colorSlider = document.getElementById('tint-color-slider');
    const alphaSlider = document.getElementById('tint-opacity-slider');

    // Toggle panel visibility
    if (tintToggleBtn) {
        tintToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            
            if (!window.selectedMaterialName) {
                window.customAlert("Please double-click a part of the object first to color it!");
                return;
            }
            
            tintPanel.classList.toggle('hidden');
        });
    }

    // Apply tint changes
    async function applyTintChange() {
        // Guard: Don't do anything if no part is selected
        if (!window.selectedMaterialName) return;

        // Get slider values
        const colorValue = colorSlider.value; // 0-100
        const alpha = alphaSlider.value / 100; // 0-1 for opacity
        
        const { updateSelectedTint, removeHighlightOnly } = await import('../main.js');
        
        // Clear blue highlight so user sees the true color
        removeHighlightOnly();
        
        // Apply the color with transparency
        updateSelectedTint(colorValue, alpha);
        
        window.showGlobalReset();
        
        // Show the reset button since we changed a property
        const resetBtn = document.getElementById('reset-textures-btn');
        if (resetBtn) resetBtn.style.display = 'flex';
    }

    if (colorSlider) colorSlider.addEventListener('input', applyTintChange);
    if (alphaSlider) alphaSlider.addEventListener('input', applyTintChange);
    
    // Reset button functionality
    const resetBtn = document.getElementById('reset-textures-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (typeof window.resetModelTextures === 'function') {
                window.resetModelTextures();
            }
            // Hide tint panel and reset sliders
            tintPanel.classList.add('hidden');
            colorSlider.value = 50; // Reset to middle
            alphaSlider.value = 100;
        });
    }

    // ========== OPEN TOOL ==========
    if (componentsTool) {
        componentsTool.addEventListener('click', () => {
            window.hideAllLists();
            if (componentsList) componentsList.style.display = 'flex';
        });
    }

    // ========== INITIALIZE ==========
    createCategoryButtons();
    console.log('✅ Component list initialized with dynamic categories');
};