@props([
    'modelType' => 'product', // 'product' or 'concept'
    'modelId' => null,
    'height' => '600px',
    'showControls' => true,
    'enableCustomization' => true,
])

<div 
    x-data="threeDViewer(@js([
        'modelType' => $modelType,
        'modelId' => $modelId,
        'showControls' => $showControls,
        'enableCustomization' => $enableCustomization,
    ]))"
    x-init="init()"
    class="relative w-full rounded-xl overflow-hidden bg-gray-100 border-2 border-indigo-200"
    style="height: {{ $height }}"
>
    <!-- Loading State -->
    <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white z-10">
        <div class="text-center">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-600 font-medium" x-text="loadingMessage">Loading 3D model...</p>
            <div class="w-64 h-2 bg-gray-200 rounded-full mx-auto mt-4 overflow-hidden">
                <div class="h-full bg-indigo-600 rounded-full transition-all duration-300" :style="`width: ${loadingProgress}%`"></div>
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div x-show="error" x-cloak class="absolute inset-0 flex items-center justify-center bg-red-50 z-10">
        <div class="text-center p-6 max-w-md">
            <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-red-600 font-semibold mb-2">Failed to load 3D model</p>
            <p class="text-red-500 text-sm mb-4" x-text="errorMessage"></p>
            <button @click="retry()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Retry
            </button>
        </div>
    </div>

    <!-- 3D Viewer Canvas Container -->
    <div x-ref="viewerContainer" class="w-full h-full" x-show="!loading && !error"></div>

    <!-- Controls Overlay (Top Right) -->
    <div x-show="showControls && !loading && !error" x-cloak class="absolute top-4 right-4 flex flex-col gap-2 z-20">
        <button @click="resetView()" 
                class="p-3 bg-white/90 backdrop-blur rounded-lg shadow-lg hover:bg-white hover:scale-110 transition-all"
                title="Reset View">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </button>
        
        <button @click="toggleFullscreen()" 
                class="p-3 bg-white/90 backdrop-blur rounded-lg shadow-lg hover:bg-white hover:scale-110 transition-all"
                title="Fullscreen">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
            </svg>
        </button>

        <button @click="captureScreenshot()" 
                class="p-3 bg-white/90 backdrop-blur rounded-lg shadow-lg hover:bg-white hover:scale-110 transition-all"
                title="Take Screenshot">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>
    </div>

    <!-- Material Selector Toggle Button -->
    <button @click="toggleMaterialSelector()" 
            x-show="enableCustomization && !loading && !error && hasMaterials" x-cloak
            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full shadow-lg hover:from-indigo-700 hover:to-purple-700 transition-all z-20 flex items-center gap-2 font-semibold">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
        </svg>
        <span x-text="showMaterialSelector ? 'Hide Materials' : 'Customize Materials'"></span>
    </button>

    <!-- Material Selector Panel (Bottom Overlay) -->
    <div x-show="showMaterialSelector && !loading && !error" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-full"
         class="absolute bottom-0 left-0 right-0 bg-white/95 backdrop-blur border-t-2 border-indigo-200 p-4 z-30 max-h-80 overflow-y-auto shadow-2xl">
        
        <!-- Instruction Text -->
        <div x-show="!selectedPart" class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
            <p class="text-sm text-amber-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><strong>Tip:</strong> Double-click on any part of the 3D model to select it, then choose a material below.</span>
            </p>
        </div>

        <!-- Selected Part Indicator -->
        <div x-show="selectedPart" class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
            <p class="text-sm text-green-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Selected part: <strong x-text="selectedPart"></strong></span>
            </p>
            <button @click="clearSelection()" class="text-green-600 hover:text-green-800 text-sm font-medium">
                Clear Selection
            </button>
        </div>

        <!-- Main Metals (Level 1) -->
        <template x-if="!selectedMainMetal">
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Select Material Type:
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <template x-for="(material, key) in materials" :key="key">
                        <button 
                            @click="selectMainMetal(key, material)"
                            class="flex flex-col items-center p-3 rounded-xl border-2 border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all group">
                            <!-- Main Metal Icon -->
                            <div class="w-16 h-16 rounded-lg overflow-hidden mb-2 bg-gray-100 ring-2 ring-gray-200 group-hover:ring-indigo-400">
                                <img :src="material.mainMetal.icon" 
                                     :alt="material.mainMetal.name"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%23999%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z%22/%3E%3C/svg%3E'">
                            </div>
                            <!-- Main Metal Name -->
                            <span class="text-xs font-semibold text-gray-700 text-center" 
                                  x-text="material.mainMetal.name"></span>
                            <!-- Sub-metals count -->
                            <span class="text-xs text-gray-500 mt-1" 
                                  x-text="material.subMetals.length + ' options'"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <!-- Sub Metals (Level 2) -->
        <template x-if="selectedMainMetal">
            <div>
                <!-- Back button and header -->
                <div class="flex items-center justify-between mb-4">
                    <button @click="selectedMainMetal = null" 
                            class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Back to Materials</span>
                    </button>
                    <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <img :src="materials[selectedMainMetal].mainMetal.icon" 
                             class="w-6 h-6 rounded object-cover"
                             onerror="this.style.display='none'">
                        <span x-text="materials[selectedMainMetal].mainMetal.name + ' Options'"></span>
                    </h4>
                </div>

                <!-- Sub-metal grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    <template x-for="subMetal in materials[selectedMainMetal].subMetals" :key="subMetal.id">
                        <button 
                            @click="applyTexture(subMetal.url, subMetal.name)"
                            class="relative group">
                            <!-- Sub-metal preview -->
                            <div class="aspect-square rounded-lg overflow-hidden border-2 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md"
                                 :class="appliedTexture === subMetal.url ? 'border-green-500 ring-2 ring-green-300' : 'border-gray-200'">
                                <img :src="subMetal.url" 
                                     :alt="subMetal.name"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%23999%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z%22/%3E%3C/svg%3E'">
                            </div>
                            <!-- Sub-metal name -->
                            <div class="mt-2 text-center">
                                <p class="text-xs font-medium text-gray-700" x-text="subMetal.name"></p>
                                <p class="text-xs text-gray-500" x-text="subMetal.ref" x-show="subMetal.ref"></p>
                            </div>
                            <!-- Selected indicator -->
                            <div x-show="appliedTexture === subMetal.url"
                                 class="absolute top-2 right-2 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    @endpush
@endonce

<script>
    document.addEventListener('alpine:init', () => {
        if (typeof Alpine === 'undefined') {
            console.error('Alpine.js is not loaded');
            return;
        }
        
        if (document.querySelector('[data-threeDViewer-initialized]')) {
            return; // Already initialized
        }
        document.body.setAttribute('data-threeDViewer-initialized', 'true');
                Alpine.data('threeDViewer', (config) => ({
                    // Config properties
                    modelType: config.modelType || 'product',
                    modelId: config.modelId || null,
                    showControls: config.showControls !== false,
                    enableCustomization: config.enableCustomization !== false,
                    
                    // State
                    loading: true,
                    loadingProgress: 0,
                    error: false,
                    loadingMessage: 'Initializing 3D viewer...',
                    errorMessage: '',
                    
                    // 3D Engine (non-reactive - prefixed with _)
                    // These are stored outside Alpine's reactivity system to avoid proxy errors
                    _scene: null,
                    _camera: null,
                    _renderer: null,
                    _controls: null,
                    _mainModel: null,
                    
                    // Materials
                    materials: {},
                    selectedMainMetal: null,
                    selectedPart: null,
                    appliedTexture: null,
                    showMaterialSelector: false,
                    hasMaterials: false,
                    
                    async init() {
                        try {
                            console.log('3D Viewer initializing...', {
                                modelType: this.modelType,
                                modelId: this.modelId,
                                showControls: this.showControls,
                                enableCustomization: this.enableCustomization
                            });
                            
                            // Wait for Three.js to be loaded
                            this.loadingMessage = 'Loading 3D libraries...';
                            this.loadingProgress = 5;
                            await this.waitForThreeJS();
                            
                            this.loadingMessage = 'Loading configuration...';
                            this.loadingProgress = 15;
                            
                            // Fetch model configuration from API
                            const response = await fetch(`/api/3d-viewer/${this.modelType}/${this.modelId}`);
                            if (!response.ok) throw new Error('Failed to fetch model configuration');
                            
                            const modelConfig = await response.json();
                            this.materials = modelConfig.components || {};
                            this.hasMaterials = Object.keys(this.materials).length > 0;
                            
                            this.loadingMessage = 'Initializing 3D scene...';
                            this.loadingProgress = 35;
                            
                            // Initialize 3D scene
                            await this.initScene();
                            
                            this.loadingMessage = 'Loading 3D model...';
                            this.loadingProgress = 55;
                            
                            // Load the main 3D model
                            await this.loadModel(modelConfig.mainModel);
                            
                            this.loadingProgress = 100;
                            this.loading = false;
                            
                        } catch (error) {
                            console.error('3D Viewer initialization error:', error);
                            this.error = true;
                            this.errorMessage = error.message || 'An unknown error occurred';
                            this.loading = false;
                        }
                    },
                    
                    async waitForThreeJS() {
                        const maxAttempts = 50;
                        let attempts = 0;
                        
                        while (typeof THREE === 'undefined' && attempts < maxAttempts) {
                            await new Promise(resolve => setTimeout(resolve, 100));
                            attempts++;
                        }
                        
                        if (typeof THREE === 'undefined') {
                            throw new Error('Three.js library failed to load');
                        }
                        
                        // Wait for loaders to be available
                        while ((typeof THREE.OBJLoader === 'undefined' || 
                                typeof THREE.MTLLoader === 'undefined' || 
                                typeof THREE.OrbitControls === 'undefined') && attempts < maxAttempts) {
                            await new Promise(resolve => setTimeout(resolve, 100));
                            attempts++;
                        }
                        
                        if (typeof THREE.OBJLoader === 'undefined') {
                            throw new Error('Three.js OBJLoader failed to load');
                        }
                    },
                    
                    async initScene() {
                        const container = this.$refs.viewerContainer;
                        
                        // Scene
                        this._scene = new THREE.Scene();
                        this._scene.background = new THREE.Color(0xeeeeee);
                        
                        // Camera
                        this._camera = new THREE.PerspectiveCamera(
                            60,
                            container.clientWidth / container.clientHeight,
                            1,
                            10000
                        );
                        this._camera.position.set(5, 5, 5);
                        
                        // Renderer
                        this._renderer = new THREE.WebGLRenderer({ 
                            antialias: true,
                            alpha: true 
                        });
                        this._renderer.setSize(container.clientWidth, container.clientHeight);
                        this._renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                        this._renderer.shadowMap.enabled = true;
                        this._renderer.shadowMap.type = THREE.PCFSoftShadowMap;
                        
                        container.appendChild(this._renderer.domElement);
                        
                        // Controls
                        this._controls = new THREE.OrbitControls(this._camera, this._renderer.domElement);
                        this._controls.enableDamping = true;
                        this._controls.dampingFactor = 0.05;
                        this._controls.minDistance = 1.6;
                        this._controls.maxDistance = 10;
                        
                        // Lights
                        const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
                        this._scene.add(ambientLight);

                        const mainLight = new THREE.DirectionalLight(0xffffff, 1.2);
                        mainLight.position.set(5, 8, 7);
                        mainLight.castShadow = true;
                        this._scene.add(mainLight);

                        const fillLight = new THREE.DirectionalLight(0xffffff, 0.5);
                        fillLight.position.set(-5, 3, -5);
                        this._scene.add(fillLight);
                        
                        // Window resize handler
                        window.addEventListener('resize', () => this.onWindowResize());
                        
                        // Double click for selection
                        this._renderer.domElement.addEventListener('dblclick', (e) => this.handleSelection(e));
                        
                        // Start animation loop
                        this.animate();
                    },
                    
                    async loadModel(modelConfig) {
                        return new Promise((resolve, reject) => {
                            const objLoader = new THREE.OBJLoader();
                            const mtlLoader = new THREE.MTLLoader();
                            
                            // The API now returns the exact path to the OBJ file
                            const objPath = modelConfig.path;
                            const basePath = objPath.substring(0, objPath.lastIndexOf('/') + 1);
                            const fileName = objPath.substring(objPath.lastIndexOf('/') + 1);
                            
                            // Try to load MTL file if it exists (same name, different extension)
                            const mtlFileName = fileName.replace('.obj', '.mtl');
                            const mtlPath = basePath + mtlFileName;
                            
                            // Attempt to load MTL first
                            mtlLoader.setPath(basePath);
                            mtlLoader.load(
                                mtlFileName,
                                (materials) => {
                                    materials.preload();
                                    objLoader.setMaterials(materials);
                                    this.loadOBJFile(objLoader, basePath, fileName, resolve, reject);
                                },
                                undefined,
                                (error) => {
                                    // MTL loading failed, try without materials
                                    console.warn('MTL file not found, loading OBJ without materials');
                                    this.loadOBJFile(objLoader, basePath, fileName, resolve, reject);
                                }
                            );
                        });
                    },
                    
                    loadOBJFile(objLoader, basePath, fileName, resolve, reject) {
                        objLoader.setPath(basePath);
                        objLoader.load(
                            fileName,
                            (object) => {
                                this._mainModel = object;
                                this._scene.add(object);
                                
                                // Fit to view
                                this.fitObjectToView(object);
                                
                                resolve(object);
                            },
                            (xhr) => {
                                const progress = (xhr.loaded / xhr.total) * 50 + 50;
                                this.loadingProgress = Math.min(progress, 99);
                            },
                            (error) => {
                                console.error('Model loading error:', error);
                                reject(new Error('Failed to load 3D model: ' + error.message));
                            }
                        );
                    },
                    
                    fitObjectToView(object) {
                        const box = new THREE.Box3().setFromObject(object);
                        const size = box.getSize(new THREE.Vector3());
                        const center = box.getCenter(new THREE.Vector3());
                        
                        const maxDim = Math.max(size.x, size.y, size.z);
                        const scale = 2.0 / maxDim;
                        object.scale.setScalar(scale);
                        
                        box.setFromObject(object);
                        box.getCenter(center);
                        object.position.sub(center);
                        
                        const distance = maxDim * 1.5;
                        this._camera.position.set(distance, distance, distance);
                        this._camera.lookAt(0, 0, 0);
                        this._controls.target.set(0, 0, 0);
                        this._controls.update();
                    },
                    
                    animate() {
                        requestAnimationFrame(() => this.animate());
                        if (this._controls) this._controls.update();
                        if (this._renderer && this._scene && this._camera) {
                            this._renderer.render(this._scene, this._camera);
                        }
                    },
                    
                    onWindowResize() {
                        if (!this._camera || !this._renderer) return;
                        const container = this.$refs.viewerContainer;
                        this._camera.aspect = container.clientWidth / container.clientHeight;
                        this._camera.updateProjectionMatrix();
                        this._renderer.setSize(container.clientWidth, container.clientHeight);
                    },
                    
                    handleSelection(event) {
                        if (!this._mainModel) return;
                        
                        const raycaster = new THREE.Raycaster();
                        const mouse = new THREE.Vector2();
                        
                        const rect = this._renderer.domElement.getBoundingClientRect();
                        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
                        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
                        
                        raycaster.setFromCamera(mouse, this._camera);
                        const intersects = raycaster.intersectObject(this._mainModel, true);
                        
                        if (intersects.length > 0) {
                            const mesh = intersects[0].object;
                            this.selectedPart = mesh.material?.name || 'Unknown Part';
                            console.log('Selected part:', this.selectedPart);
                        }
                    },
                    
                    selectMainMetal(metalKey, material) {
                        this.selectedMainMetal = metalKey;
                        console.log('Selected main metal:', material.mainMetal.name);
                    },
                    
                    applyTexture(textureUrl, textureName) {
                        if (!this.selectedPart) {
                            alert('Please double-click on a part of the 3D model first to select it!');
                            return;
                        }
                        
                        // Apply texture to selected parts
                        const loader = new THREE.TextureLoader();
                        loader.load(textureUrl, (texture) => {
                            texture.encoding = THREE.sRGBEncoding;
                            texture.wrapS = THREE.RepeatWrapping;
                            texture.wrapT = THREE.RepeatWrapping;
                            
                            this._mainModel.traverse((child) => {
                                if (child.isMesh && child.material.name === this.selectedPart) {
                                    child.material.map = texture;
                                    child.material.needsUpdate = true;
                                }
                            });
                            
                            this.appliedTexture = textureUrl;
                            console.log(`Applied ${textureName} to ${this.selectedPart}`);
                        });
                    },
                    
                    clearSelection() {
                        this.selectedPart = null;
                        this.appliedTexture = null;
                    },
                    
                    toggleMaterialSelector() {
                        this.showMaterialSelector = !this.showMaterialSelector;
                        if (!this.showMaterialSelector) {
                            this.selectedMainMetal = null;
                        }
                    },
                    
                    resetView() {
                        if (this._camera && this._controls) {
                            this._camera.position.set(5, 5, 5);
                            this._controls.target.set(0, 0, 0);
                            this._controls.update();
                        }
                    },
                    
                    toggleFullscreen() {
                        const element = this.$el;
                        if (!document.fullscreenElement) {
                            element.requestFullscreen();
                        } else {
                            document.exitFullscreen();
                        }
                    },
                    
                    captureScreenshot() {
                        if (!this._renderer) return;
                        this._renderer.render(this._scene, this._camera);
                        const dataURL = this._renderer.domElement.toDataURL('image/png');
                        const link = document.createElement('a');
                        link.download = `3d-model-screenshot-${Date.now()}.png`;
                        link.href = dataURL;
                        link.click();
                    },
                    
                    async retry() {
                        this.error = false;
                        this.loading = true;
                        this.loadingProgress = 0;
                        await this.init();
                    }
                }));
    });
</script>
