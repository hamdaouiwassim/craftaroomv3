<x-main-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <!-- Concept Details Header -->
    <section class="relative bg-gradient-to-br from-purple-900 via-pink-900 to-rose-900 text-white py-12 lg:py-16 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('concepts.index') }}" class="flex items-center gap-2 text-purple-200 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Back to Concepts</span>
                </a>
            </div>
            <div class="flex items-center gap-3 mb-3">
                @if($concept->source === 'designer')
                    <span class="px-3 py-1 bg-purple-500/30 backdrop-blur-sm border border-purple-300/50 rounded-full text-sm font-semibold">
                        Designer Concept
                    </span>
                @else
                    <span class="px-3 py-1 bg-indigo-500/30 backdrop-blur-sm border border-indigo-300/50 rounded-full text-sm font-semibold">
                        Library Collection
                    </span>
                @endif
            </div>
            <h1 class="text-3xl md:text-5xl font-bold mb-2 bg-gradient-to-r from-white via-purple-100 to-pink-100 bg-clip-text text-transparent">
                {{ $concept->name }}
            </h1>
            @if($concept->category)
                <p class="text-purple-200 text-lg">{{ $concept->category->name }}</p>
            @endif
        </div>
    </section>

    <!-- Concept Details Content -->
    <section class="py-16 bg-gradient-to-b from-white via-purple-50/30 to-pink-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Concept Images & Media -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Main Concept Images -->
                    @if($concept->photos->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Photos
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($concept->photos->take(6) as $photo)
                                    <div class="relative group overflow-hidden rounded-xl bg-gradient-to-br from-purple-100 via-pink-100 to-rose-100">
                                        <img src="{{ $photo->url }}" 
                                             alt="{{ $concept->name }}"
                                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer"
                                             onclick="window.open('{{ $photo->url }}', '_blank')">
                                    </div>
                                @endforeach
                            </div>
                            @if($concept->photos->count() > 6)
                                <p class="text-center text-sm text-gray-500 mt-4">
                                    +{{ $concept->photos->count() - 6 }} more photos
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- 3D Model -->
                    @if($concept->threedmodels)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">3D Model</h3>
                            </div>
                            <div class="flex items-center gap-3 p-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-200">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $concept->threedmodels->name ?? '3D Model File' }}</p>
                                    <p class="text-sm text-gray-600">ZIP Archive</p>
                                </div>
                                <a href="{{ $concept->threedmodels->url }}" 
                                   download
                                   class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg font-semibold hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 shadow-md hover:shadow-lg">
                                    Download
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Reel Video -->
                    @if($concept->reel)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-gradient-to-br from-pink-100 to-rose-100 rounded-lg">
                                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Video Reel</h3>
                            </div>
                            <div class="rounded-xl overflow-hidden bg-black">
                                <video controls class="w-full h-auto">
                                    <source src="{{ $concept->reel }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    @endif

                    <!-- Concept Description -->
                    @if($concept->description)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                </svg>
                                Description
                            </h3>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $concept->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Concept Info -->
                <div class="space-y-6">
                    <!-- Concept Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Concept Information
                        </h3>

                        <div class="space-y-4">
                            <!-- Source -->
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Source</p>
                                <div class="flex items-center gap-2">
                                    @if($concept->source === 'designer')
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-sm font-semibold">
                                            Designer Concept
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-semibold">
                                            Library Collection
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Category -->
                            @if($concept->category)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Category</p>
                                    <p class="font-semibold text-gray-900">{{ $concept->category->name }}</p>
                                </div>
                            @endif

                            <!-- Rooms -->
                            @if($concept->rooms && $concept->rooms->count() > 0)
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Suitable Rooms</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($concept->rooms as $room)
                                            <span class="px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-lg text-sm font-medium">
                                                {{ $room->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Metals -->
                            @if($concept->metals && $concept->metals->count() > 0)
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Materials</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($concept->metals as $metal)
                                            <span class="px-3 py-1 bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 rounded-lg text-sm font-medium">
                                                {{ $metal->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Dimensions -->
                            @if($concept->measure && $concept->measure->dimension)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Dimensions</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $concept->measure->dimension->length }} × 
                                        {{ $concept->measure->dimension->width }} × 
                                        {{ $concept->measure->dimension->height }} 
                                        {{ $concept->measure->dimension->unit }}
                                    </p>
                                </div>
                            @endif

                            <!-- Weight -->
                            @if($concept->measure && $concept->measure->weight)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Weight</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $concept->measure->weight->weight_value }} {{ $concept->measure->weight->weight_unit }}
                                    </p>
                                </div>
                            @endif

                            <!-- Size -->
                            @if($concept->measure && $concept->measure->size)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Size</p>
                                    <p class="font-semibold text-gray-900">{{ $concept->measure->size }}</p>
                                </div>
                            @endif

                            <!-- Creator Info -->
                            @if($concept->user && $concept->source === 'designer')
                                <div class="pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600 mb-2">Created by</p>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-200 to-pink-200 flex items-center justify-center overflow-hidden">
                                            @if($concept->user->photoUrl)
                                                <img src="{{ $concept->user->photoUrl }}" alt="{{ $concept->user->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-sm font-bold text-purple-600">{{ substr($concept->user->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $concept->user->name }}</p>
                                            <p class="text-xs text-gray-500">Designer</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Request Construction Button -->
                        <div class="mt-6">
                            @auth
                                @if(auth()->user()->role === 2) {{-- Only customers can request --}}
                                    <button 
                                        onclick="openConstructionRequestModal()"
                                        class="w-full py-4 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 text-white rounded-xl font-bold text-lg hover:from-purple-600 hover:via-pink-600 hover:to-rose-600 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105 flex items-center justify-center gap-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Request Construction
                                    </button>
                                    <p class="text-xs text-gray-500 text-center mt-2">
                                        Send this concept to all constructors for quotes
                                    </p>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full py-4 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-xl font-bold text-lg text-center hover:from-gray-500 hover:to-gray-600 transition-all duration-300 shadow-lg">
                                    Login to Request Construction
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Concepts -->
            @if($relatedConcepts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Related Concepts
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($relatedConcepts as $relatedConcept)
                            <a href="{{ route('concepts.show', $relatedConcept->id) }}" class="group bg-white rounded-2xl border-2 border-purple-100 overflow-hidden shadow-md hover:shadow-2xl hover:border-purple-300 transition-all duration-300 transform hover:-translate-y-2">
                                <div class="aspect-square bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center overflow-hidden">
                                    @if($relatedConcept->photos->count() > 0)
                                        <img src="{{ $relatedConcept->photos->first()->url }}" 
                                             alt="{{ $relatedConcept->name }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <svg class="w-16 h-16 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-purple-600 transition-colors line-clamp-1">
                                        {{ $relatedConcept->name }}
                                    </h3>
                                    @if($relatedConcept->category)
                                        <p class="text-sm text-gray-600">{{ $relatedConcept->category->name }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        function openConstructionRequestModal() {
            document.getElementById('constructionRequestModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeConstructionRequestModal() {
            document.getElementById('constructionRequestModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConstructionRequestModal();
            }
        });
    </script>

    <!-- Construction Request Modal -->
    <div id="constructionRequestModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeConstructionRequestModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Request Construction</h3>
                    <button type="button" onclick="closeConstructionRequestModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('construction-requests.store', $concept->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                        <div class="flex items-start gap-3 mb-3">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-purple-900 mb-1">Concept: {{ $concept->name }}</p>
                                <p class="text-sm text-purple-700">Your request will be sent to all constructors on the platform. They will review your request and may contact you with quotes and timelines.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                            Message (Optional)
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="3"
                            placeholder="Briefly describe what you're looking for..."
                            class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white resize-none"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Example: "I need this for my living room, approximate budget is..."</p>
                    </div>

                    <div>
                        <label for="customer_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea 
                            id="customer_notes" 
                            name="customer_notes" 
                            rows="4"
                            placeholder="Any specific requirements, dimensions, materials preferences, timeline, etc..."
                            class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white resize-none"></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <button 
                            type="button"
                            onclick="closeConstructionRequestModal()"
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 text-white rounded-xl font-bold hover:from-purple-600 hover:via-pink-600 hover:to-rose-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                            Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>
