// floorlist.js

import { getFloorsConfig, setFloorsConfig, getFloorCategories } from '../api.js';

window.initFloorList = function() {
    const floorList = document.getElementById('floor-list');
    const floorImageList = document.getElementById('floor-image-list');
    const floorItemsContainer = document.querySelector('.floor-items-container');
    const floorTool = document.getElementById('floor-tool');
    const scaleBtn = document.getElementById('floor-scale-btn');
    const resetFloorBtn = document.getElementById('reset-floor-btn');

    // ========== DEFAULT CATEGORY ICONS (fallback) ==========
    const defaultCategoryIcons = {
        "floor-simple": 'https://icon-library.com/images/floor-icon/floor-icon-11.jpg',
        "floor-carpet": 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR6xNAme4TcyUuUyp8AIQVyTz67y-tVO3-M7A&s',
        "floor-table": 'https://cdn-icons-png.flaticon.com/512/607/607069.png',
        "floor-tile": 'https://cdn-icons-png.flaticon.com/512/685/685655.png',
        "floor-wood": 'https://cdn-icons-png.flaticon.com/512/2917/2917242.png',
        "floor-marble": 'https://cdn-icons-png.flaticon.com/512/3050/3050270.png',
        "floor-concrete": 'https://cdn-icons-png.flaticon.com/512/2917/2917995.png'
    };
    
    // Get categories from database (if loaded from API)
    const apiCategories = getFloorCategories();

    function updateResetButtonVisibility() {
        if (resetFloorBtn) {
            if (window.lastSelectedFloor && window.lastSelectedFloor.folderPath) {
                resetFloorBtn.style.display = 'flex';
            } else {
                resetFloorBtn.style.display = 'none';
            }
        }
    }

    // ========== GET CONFIGURATION FROM API ==========
    let floorImagesData = getFloorsConfig();
    
    // Default configuration if none is set via API
    if (!floorImagesData) {
        console.warn('⚠️ No floors configured via API. Using default configuration.');
        floorImagesData = {
            "floor-simple": [
                {url:"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSe6a0XOO_kvDu3EXEFGZmOS0vq2Adw9wFarA&s", folderPath: "objects/woodfloor/", fileName: "woodfloor.obj", baseSize: 5.0},
                {url:"https://www.angelfurniture.in/image/cache/catalog/Products/AF-250/AF250%20(1)-550x550w.jpg", folderPath: "objects/woodfloor2/", fileName: "woodfloor2.obj", baseSize: 2.0},
                {url:"https://media.homecentre.com/i/homecentre/165555909-165555909-HC18052023_02-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/woodfloor3/", fileName: "woodfloor3.obj", baseSize: 2.0},
                {url:"https://media.homeboxstores.com/i/homebox/165707654-165707654-HMBX24092023N_01-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/woodfloor4/", fileName: "woodfloor4.obj", baseSize: 2.0}
            ],
            "floor-carpet": [
                {url:"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSe6a0XOO_kvDu3EXEFGZmOS0vq2Adw9wFarA&s", folderPath: "objects/carpet1/", fileName: "carpet1.obj", baseSize: 2.0},
                {url:"https://www.angelfurniture.in/image/cache/catalog/Products/AF-250/AF250%20(1)-550x550w.jpg", folderPath: "objects/carpet2/", fileName: "carpet2.obj", baseSize: 2.0},
                {url:"https://media.homecentre.com/i/homecentre/165555909-165555909-HC18052023_02-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/carpet3/", fileName: "carpet3.obj", baseSize: 2.0},
                {url:"https://media.homeboxstores.com/i/homebox/165707654-165707654-HMBX24092023N_01-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/carpet4/", fileName: "carpet4.obj", baseSize: 2.0}
            ],
            "floor-table": [
                {url:"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSe6a0XOO_kvDu3EXEFGZmOS0vq2Adw9wFarA&s", folderPath: "objects/DiningTable1/", fileName: "DiningTable1.obj", baseSize: 2.0},
                {url:"https://www.angelfurniture.in/image/cache/catalog/Products/AF-250/AF250%20(1)-550x550w.jpg", folderPath: "objects/roundTable1/", fileName: "roundTable1.obj", baseSize: 2.0},
                {url:"https://media.homecentre.com/i/homecentre/165555909-165555909-HC18052023_02-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/tableOffice2/", fileName: "tableOffice2.obj", baseSize: 2.0},
                {url:"https://media.homeboxstores.com/i/homebox/165707654-165707654-HMBX24092023N_01-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/smDrawer1/", fileName: "smDrawer1.obj", baseSize: 2.0}
            ],
            "floor-tile": [
                {url:"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSe6a0XOO_kvDu3EXEFGZmOS0vq2Adw9wFarA&s", folderPath: "objects/tilefloor1/", fileName: "tilefloor1.obj", baseSize: 2.0},
                {url:"https://www.angelfurniture.in/image/cache/catalog/Products/AF-250/AF250%20(1)-550x550w.jpg", folderPath: "objects/tilefloor2/", fileName: "tilefloor2.obj", baseSize: 2.0},
                {url:"https://media.homecentre.com/i/homecentre/165555909-165555909-HC18052023_02-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/tilefloor3/", fileName: "tilefloor3.obj", baseSize: 2.0},
                {url:"https://media.homeboxstores.com/i/homebox/165707654-165707654-HMBX24092023N_01-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/tilefloor4/", fileName: "tilefloor4.obj", baseSize: 2.0}
            ]
        };
        setFloorsConfig(floorImagesData);
    }
    
    console.log('✅ Floors data loaded:', Object.keys(floorImagesData));

    // ========== DYNAMICALLY CREATE CATEGORY BUTTONS ==========
    function createFloorCategoryButtons() {
        // Remove old category buttons if they exist (except reset button)
        const oldButtons = floorItemsContainer.querySelectorAll('.floor-item:not(#reset-floor-btn)');
        oldButtons.forEach(btn => btn.remove());

        // Create buttons for each floor category in config
        Object.keys(floorImagesData).forEach(categoryKey => {
            const div = document.createElement('div');
            div.className = `tool-item floor-item ${categoryKey}`;
            
            const img = document.createElement('img');
            // Use icon from database if available, otherwise use default
            const categoryInfo = apiCategories?.[categoryKey];
            const iconUrl = categoryInfo?.icon || defaultCategoryIcons[categoryKey] || 'https://cdn-icons-png.flaticon.com/512/685/685655.png';
            img.src = iconUrl;
            img.className = 'floor-icon';
            img.alt = categoryKey;
            
            const label = document.createElement('div');
            label.className = 'tool-label';
            // Use name from database if available, otherwise format the key
            const displayName = categoryInfo?.name || 
                               (categoryKey.replace('floor-', '').charAt(0).toUpperCase() + 
                                categoryKey.replace('floor-', '').slice(1));
            label.textContent = displayName;
            
            div.appendChild(img);
            div.appendChild(label);
            floorItemsContainer.appendChild(div);
            
            // Add click listener
            div.addEventListener('click', () => {
                // Remove selected from all
                floorItemsContainer.querySelectorAll('.floor-item').forEach(i => i.classList.remove('selected'));
                // Add selected to clicked
                div.classList.add('selected');
                // Show images
                showFloorImages(categoryKey);
                window.showFloorScale();
            });
        });
    }

    function showFloorImages(floorClass) {
        floorImageList.innerHTML = '';
        if (!floorImagesData[floorClass]) {
            floorImageList.style.display = 'none';
            return;
        }
        floorImageList.style.display = 'flex';

        floorImagesData[floorClass].forEach(data => {
            const img = document.createElement('img');
            img.src = data.url;
            img.addEventListener('click', async () => {
                floorImageList.querySelectorAll('img').forEach(i => i.classList.remove('selected'));
                img.classList.add('selected');

                window.lastSelectedFloor = {
                    folderPath: data.folderPath,
                    fileName: data.fileName,
                    baseSize: parseFloat(data.baseSize) || 1
                };

                try {
                    const { loadFloorModel } = await import('../main.js');
                    await loadFloorModel({
                        folderPath: window.lastSelectedFloor.folderPath,
                        fileName: window.lastSelectedFloor.fileName,
                        desiredSize: window.lastSelectedFloor.baseSize * window.currentScale,
                    });
                    
                    updateResetButtonVisibility();
                    window.showGlobalReset();

                } catch (err) { console.error(err); }
            });
            floorImageList.appendChild(img);
        });
    }

    // Navigation trigger
    if (floorTool) {
        floorTool.addEventListener('click', () => {
            window.hideAllLists();
            if (floorList) {
                floorList.style.display = 'flex';
                window.showFloorScale();
                window.selectSidebarItem('floor-tool');
                updateResetButtonVisibility();
            }
        });
    }

    // Reset button click
    if (resetFloorBtn) {
        resetFloorBtn.addEventListener('click', async (e) => {
            e.stopPropagation();
            const { removeFloorModel } = await import('../main.js');
            removeFloorModel();

            floorItemsContainer.querySelectorAll('.floor-item').forEach(i => i.classList.remove('selected'));
            floorImageList.style.display = 'none';
            updateResetButtonVisibility();
        });
    }

    // Scale Button logic
    if (scaleBtn) {
        scaleBtn.addEventListener('click', async () => {
            window.currentScale += 0.5;
            if (window.currentScale > window.maxScale) window.currentScale = 1;
            scaleBtn.textContent = `x${4 - window.currentScale}`;

            if (window.lastSelectedFloor.folderPath && window.lastSelectedFloor.fileName) {
                try {
                    const { loadFloorModel } = await import('../main.js');
                    await loadFloorModel({
                        folderPath: window.lastSelectedFloor.folderPath,
                        fileName: window.lastSelectedFloor.fileName,
                        desiredSize: window.lastSelectedFloor.baseSize * window.currentScale,
                    });
                    updateResetButtonVisibility();
                } catch (err) { console.error(err); }
            }
        });
    }

    // ========== INITIALIZE ==========
    createFloorCategoryButtons();
    console.log('✅ Floor list initialized with dynamic categories');
};
