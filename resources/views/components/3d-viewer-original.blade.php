@props([
    'modelType' => 'product', // 'product' or 'concept'
    'modelId' => null,
    'height' => '600px',
])

@if($modelId)
<div x-data="{
    fullscreen: false,
    viewerState: null,
    arMessage: '',
    arMessageVisible: false,
    arMessageTimeout: null,
    async captureState(frame) {
        const iframeWindow = frame?.contentWindow;

        if (!iframeWindow || typeof iframeWindow.captureCustomizationState !== 'function') {
            return null;
        }

        try {
            return await iframeWindow.captureCustomizationState();
        } catch (error) {
            return null;
        }
    },
    async restoreState(frame, state = this.viewerState) {
        const iframeWindow = frame?.contentWindow;

        if (!iframeWindow || !state) {
            return;
        }

        for (let attempt = 0; attempt < 40; attempt++) {
            if (typeof iframeWindow.restoreCustomizationState === 'function') {
                try {
                    await iframeWindow.restoreCustomizationState(state);
                } catch (error) {
                }

                return;
            }

            await new Promise(resolve => setTimeout(resolve, 150));
        }
    },
    async enterFullscreen() {
        this.viewerState = await this.captureState(this.$refs.normalIframe);
        this.fullscreen = true;
        document.body.style.overflow = 'hidden';

        this.$nextTick(() => {
            this.restoreState(this.$refs.fullscreenIframe);
        });
    },
    async exitFullscreen() {
        this.viewerState = await this.captureState(this.$refs.fullscreenIframe) ?? this.viewerState;
        this.fullscreen = false;
        document.body.style.overflow = '';

        this.$nextTick(() => {
            this.restoreState(this.$refs.normalIframe);
        });
    },
    async syncFromFullscreen() {
        if (!this.fullscreen) {
            return;
        }

        await this.restoreState(this.$refs.fullscreenIframe);
    },
    async syncFromNormal() {
        if (this.fullscreen) {
            return;
        }

        await this.restoreState(this.$refs.normalIframe);
    },
    showArMessage(message) {
        this.arMessage = message;
        this.arMessageVisible = true;

        if (this.arMessageTimeout) {
            clearTimeout(this.arMessageTimeout);
        }

        this.arMessageTimeout = setTimeout(() => {
            this.arMessageVisible = false;
        }, 3200);
    },
    async triggerAr() {
        const frame = this.fullscreen ? this.$refs.fullscreenIframe : this.$refs.normalIframe;
        const iframeWindow = frame?.contentWindow;

        console.log('[3D Viewer] View AR clicked', {
            fullscreen: this.fullscreen,
            hasFrame: !!frame,
            iframeLoaded: !!iframeWindow,
            hasExporter: !!iframeWindow && typeof iframeWindow.exportCurrentModelAsGlb === 'function'
        });

        if (!iframeWindow || typeof iframeWindow.exportCurrentModelAsGlb !== 'function') {
            this.showArMessage('GLB export is not available for this viewer yet.');
            return;
        }

        try {
            this.showArMessage('Converting current 3D model to GLB...');
            await iframeWindow.exportCurrentModelAsGlb();
            this.showArMessage('GLB file downloaded successfully.');
        } catch (error) {
            console.error('[3D Viewer] GLB export failed', error);
            this.showArMessage(error?.message || 'Failed to convert the current 3D model to GLB.');
        }
    }
}" class="relative">
    {{-- Normal view --}}
    <div x-show="!fullscreen" class="w-full rounded-xl overflow-hidden bg-gray-100 border-2 border-indigo-200 shadow-lg" style="height: {{ $height }}">
        <iframe 
            x-ref="normalIframe"
            @load="syncFromNormal()"
            src="{{ asset('3d-engine/index.html') }}?type={{ $modelType }}&id={{ $modelId }}"
            class="w-full h-full border-0"
            allowfullscreen
            loading="lazy"
            title="3D Model Viewer">
        </iframe>
    </div>

    <div class="absolute top-3 left-1/2 -translate-x-1/2 z-20">
        <button
            type="button"
            @click.stop.prevent="triggerAr()"
            class="inline-flex items-center gap-2 rounded-full bg-white/90 px-4 py-2 text-sm font-semibold text-gray-800 shadow-md backdrop-blur-sm transition-all duration-200 hover:bg-white hover:shadow-lg"
            title="View AR">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16V8a2 2 0 00-1.106-1.789l-7-3.5a2 2 0 00-1.788 0l-7 3.5A2 2 0 003 8v8a2 2 0 001.106 1.789l7 3.5a2 2 0 001.788 0l7-3.5A2 2 0 0021 16z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.27 6.96L12 11.5l8.73-4.54M12 22.08V11.5" />
            </svg>
            <span>View AR</span>
        </button>
    </div>

    {{-- Fullscreen button --}}
    <button 
        x-show="!fullscreen"
        @click="enterFullscreen()"
        class="absolute top-3 right-3 z-10 p-2 bg-white/80 backdrop-blur-sm rounded-lg shadow-md hover:bg-white hover:shadow-lg transition-all duration-200 group"
        title="Fullscreen">
        <svg class="w-5 h-5 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        </svg>
    </button>

    {{-- Fullscreen overlay --}}
    <div 
        x-show="fullscreen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="exitFullscreen()"
        class="fixed inset-0 z-50 bg-black"
        style="display: none;">

        <div class="absolute top-4 left-1/2 -translate-x-1/2 z-[60]">
            <button
                type="button"
                @click.stop.prevent="triggerAr()"
                class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold text-white shadow-md backdrop-blur-md transition-all duration-200 hover:bg-white/25"
                title="View AR">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16V8a2 2 0 00-1.106-1.789l-7-3.5a2 2 0 00-1.788 0l-7 3.5A2 2 0 003 8v8a2 2 0 001.106 1.789l7 3.5a2 2 0 001.788 0l7-3.5A2 2 0 0021 16z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.27 6.96L12 11.5l8.73-4.54M12 22.08V11.5" />
                </svg>
                <span>View AR</span>
            </button>
        </div>
        
        {{-- Close button --}}
        <button 
            @click="exitFullscreen()"
            class="absolute top-4 right-4 z-[60] p-2.5 bg-white/10 backdrop-blur-md rounded-full hover:bg-white/25 transition-all duration-200 group"
            title="Exit fullscreen (Esc)">
            <svg class="w-6 h-6 text-white group-hover:text-red-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Fullscreen iframe --}}
        <iframe 
            x-ref="fullscreenIframe"
            @load="syncFromFullscreen()"
            src="{{ asset('3d-engine/index.html') }}?type={{ $modelType }}&id={{ $modelId }}"
            class="w-full h-full border-0"
            allowfullscreen
            title="3D Model Viewer - Fullscreen">
        </iframe>
    </div>

    <div
        x-show="arMessageVisible"
        x-transition
        x-cloak
        class="absolute top-16 left-1/2 z-[70] w-[min(90%,28rem)] -translate-x-1/2 rounded-xl border border-indigo-200 bg-white/95 px-4 py-3 text-center text-sm font-medium text-gray-800 shadow-xl backdrop-blur">
        <span x-text="arMessage"></span>
    </div>
</div>
@else
<div class="w-full rounded-xl overflow-hidden bg-red-50 border-2 border-red-200 p-8 text-center" style="height: {{ $height }}">
    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
    <p class="text-red-600 font-semibold">No model ID provided</p>
</div>
@endif
