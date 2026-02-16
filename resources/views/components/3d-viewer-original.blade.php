@props([
    'modelType' => 'product', // 'product' or 'concept'
    'modelId' => null,
    'height' => '600px',
])

@if($modelId)
<div x-data="{ fullscreen: false }" class="relative">
    {{-- Normal view --}}
    <div x-show="!fullscreen" class="w-full rounded-xl overflow-hidden bg-gray-100 border-2 border-indigo-200 shadow-lg" style="height: {{ $height }}">
        <iframe 
            src="{{ asset('3d-engine/index.html') }}?type={{ $modelType }}&id={{ $modelId }}"
            class="w-full h-full border-0"
            allowfullscreen
            loading="lazy"
            title="3D Model Viewer">
        </iframe>
    </div>

    {{-- Fullscreen button --}}
    <button 
        x-show="!fullscreen"
        @click="fullscreen = true; document.body.style.overflow = 'hidden'"
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
        @keydown.escape.window="fullscreen = false; document.body.style.overflow = ''"
        class="fixed inset-0 z-50 bg-black"
        style="display: none;">
        
        {{-- Close button --}}
        <button 
            @click="fullscreen = false; document.body.style.overflow = ''"
            class="absolute top-4 right-4 z-[60] p-2.5 bg-white/10 backdrop-blur-md rounded-full hover:bg-white/25 transition-all duration-200 group"
            title="Exit fullscreen (Esc)">
            <svg class="w-6 h-6 text-white group-hover:text-red-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Fullscreen iframe --}}
        <iframe 
            x-ref="fullscreenIframe"
            src="{{ asset('3d-engine/index.html') }}?type={{ $modelType }}&id={{ $modelId }}"
            class="w-full h-full border-0"
            allowfullscreen
            title="3D Model Viewer - Fullscreen">
        </iframe>
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
