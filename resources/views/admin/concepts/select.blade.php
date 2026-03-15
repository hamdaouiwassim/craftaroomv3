<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                    {{ $source === 'library' ? 'Concepts de la bibliothèque' : 'Concepts designers' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="conceptSelector()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
                <div class="p-6">
                    <p class="text-gray-600 mb-6">
                        {{ $source === 'library' ? 'Choisissez un concept de la bibliothèque pour créer un produit. Le formulaire sera pré-rempli avec les informations du concept (modifiables).' : 'Choisissez un concept créé par un designer pour créer un produit. Le formulaire sera pré-rempli avec les informations du concept (modifiables).' }}
                    </p>

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="relative max-w-xl">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                x-model="searchQuery"
                                @input="filterConcepts"
                                placeholder="Rechercher un concept par nom, catégorie ou description..."
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition-all"
                            >
                            <div x-show="searchQuery" @click="searchQuery = ''; filterConcepts()" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                        <p x-show="filteredCount !== totalCount" class="mt-2 text-sm text-teal-600" x-cloak>
                            <span x-text="filteredCount"></span> concept(s) trouvé(s) sur <span x-text="totalCount"></span>
                        </p>
                    </div>

                    @if($concepts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="concepts-grid">
                            @foreach($concepts as $concept)
                                <div class="concept-card bg-white rounded-xl border-2 border-teal-100 overflow-hidden shadow-sm hover:border-teal-300 hover:shadow-md transition-all" data-concept-id="{{ $concept->id }}" data-concept-name="{{ strtolower($concept->name) }}" data-concept-category="{{ strtolower($concept->category?->name ?? '') }}" data-concept-description="{{ strtolower($concept->description ?? '') }}">
                                    <div class="aspect-video bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center overflow-hidden relative group">
                                        @if($concept->photos->count() > 0)
                                            <img src="{{ $concept->photos->first()->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-16 h-16 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @endif
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button @click="openModal({{ $concept->id }})" class="px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold hover:bg-gray-100 transition-all flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-900 truncate">{{ $concept->name }}</h3>
                                        @if($concept->category)
                                            <p class="text-sm text-gray-500 mt-1">{{ $concept->category->name }}</p>
                                        @endif
                                        <div class="mt-3 flex items-center gap-3 rounded-xl border border-teal-100 bg-teal-50/70 px-3 py-2">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-200 to-cyan-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                @if($concept->source === 'designer' && $concept->user?->photoUrl)
                                                    <img src="{{ $concept->user->photoUrl }}" alt="{{ $concept->user->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-sm font-bold text-teal-700">{{ $concept->source === 'designer' ? substr($concept->user->name ?? 'D', 0, 1) : 'C' }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-semibold uppercase tracking-wide text-teal-600">Owner</p>
                                                @if($concept->source === 'designer' && $concept->user)
                                                    <a href="{{ route('designer.show', $concept->user->id) }}" class="block truncate text-sm font-semibold text-gray-800 hover:text-teal-700 transition-colors">
                                                        {{ $concept->user->name }}
                                                    </a>
                                                @else
                                                    <p class="truncate text-sm font-semibold text-gray-800">CraftARoom</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($concept->description)
                                            <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ Str::limit($concept->description, 80) }}</p>
                                        @endif
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if($concept->rooms && $concept->rooms->count() > 0)
                                                <span class="inline-flex items-center text-xs px-2 py-1 bg-teal-100 text-teal-700 rounded">
                                                    {{ $concept->rooms->count() }} pièce(s)
                                                </span>
                                            @endif
                                            @if($concept->metals && $concept->metals->count() > 0)
                                                <span class="inline-flex items-center text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">
                                                    {{ $concept->metals->count() }} métal(aux)
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-4 flex gap-2">
                                            <button @click="openModal({{ $concept->id }})" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </button>
                                            <a href="{{ route('admin.products.create', ['concept_id' => $concept->id]) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-lg font-semibold hover:from-teal-600 hover:to-cyan-600 transition-all text-sm">
                                                Utiliser
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Empty State for Search -->
                        <div x-show="filteredCount === 0 && searchQuery" class="text-center py-12 bg-white rounded-xl border border-teal-100" x-cloak>
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="text-gray-600 mb-2">Aucun concept ne correspond à votre recherche.</p>
                            <button @click="searchQuery = ''; filterConcepts()" class="text-teal-600 hover:text-teal-700 font-medium">
                                Effacer la recherche
                            </button>
                        </div>

                        <div class="mt-6" x-show="!searchQuery">{{ $concepts->links() }}</div>
                    @else
                        <div class="text-center py-12 bg-white rounded-xl border border-teal-100">
                            <p class="text-gray-600 mb-4">
                                {{ $source === 'library' ? 'Aucun concept dans la bibliothèque pour le moment.' : 'Aucun concept designer disponible.' }}
                            </p>
                            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                                Retour aux produits
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Concept Detail Modal -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="modalOpen" @click="closeModal()" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-4 py-3 sm:px-6 flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-bold text-white" id="modal-title" x-text="selectedConcept?.name"></h3>
                        <button @click="closeModal()" class="text-white hover:text-teal-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-4 py-5 sm:p-6 max-h-[70vh] overflow-y-auto">
                        <div x-show="loading" class="flex items-center justify-center py-12">
                            <svg class="animate-spin h-8 w-8 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <div x-show="!loading && selectedConcept" class="space-y-6">
                            <!-- Images -->
                            <div x-show="selectedConcept?.photos?.length > 0">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Photos</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <template x-for="photo in selectedConcept?.photos" :key="photo.id">
                                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                                            <img :src="photo.url" class="w-full h-full object-cover" alt="">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Category -->
                            <div x-show="selectedConcept?.category">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Catégorie</h4>
                                <p class="text-gray-900" x-text="selectedConcept?.category?.name"></p>
                            </div>

                            <!-- Description -->
                            <div x-show="selectedConcept?.description">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Description</h4>
                                <p class="text-gray-700 whitespace-pre-line" x-text="selectedConcept?.description"></p>
                            </div>

                            <!-- Owner -->
                            <div x-show="selectedConcept?.owner" class="rounded-2xl border border-teal-100 bg-gradient-to-r from-teal-50 to-cyan-50 p-4">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Owner</h4>
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-teal-200 to-cyan-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            <template x-if="selectedConcept?.owner?.photo_url">
                                                <img :src="selectedConcept?.owner?.photo_url" :alt="selectedConcept?.owner?.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!selectedConcept?.owner?.photo_url">
                                                <span class="text-lg font-bold text-teal-700" x-text="selectedConcept?.owner?.type === 'designer' ? (selectedConcept?.owner?.name || 'D').charAt(0) : 'C'"></span>
                                            </template>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-teal-600" x-text="selectedConcept?.owner?.type === 'designer' ? 'Designer' : 'Library Owner'"></p>
                                            <p class="truncate text-lg font-bold text-gray-900" x-text="selectedConcept?.owner?.name"></p>
                                        </div>
                                    </div>
                                    <a x-show="selectedConcept?.owner?.profile_url"
                                       :href="selectedConcept?.owner?.profile_url"
                                       class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-teal-700 shadow-sm transition-colors hover:text-teal-800">
                                        View Profile
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Rooms -->
                            <div x-show="selectedConcept?.rooms?.length > 0">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Pièces</h4>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="room in selectedConcept?.rooms" :key="room.id">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800" x-text="room.name"></span>
                                    </template>
                                </div>
                            </div>

                            <!-- Metals -->
                            <div x-show="selectedConcept?.metals?.length > 0">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Métaux</h4>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="metal in selectedConcept?.metals" :key="metal.id">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800" x-text="metal.name"></span>
                                    </template>
                                </div>
                            </div>

                            <!-- 3D Model -->
                            <div x-show="selectedConcept?.has_model">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Modèle 3D</h4>
                                <div class="relative w-full rounded-xl overflow-hidden bg-gray-100 border-2 border-teal-200" style="height: 400px;"
                                     x-data="{ fullscreen: false }">
                                    <!-- Normal view -->
                                    <div x-show="!fullscreen" class="w-full h-full">
                                        <iframe
                                            :src="`/3d-engine/index.html?type=concept&id=${selectedConcept?.id}`"
                                            class="w-full h-full border-0"
                                            allowfullscreen
                                            loading="lazy"
                                            title="3D Model Viewer">
                                        </iframe>
                                    </div>
                                    <!-- Fullscreen overlay -->
                                    <div x-show="fullscreen"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         @keydown.escape.window="fullscreen = false"
                                         class="fixed inset-0 z-[60] bg-black"
                                         style="display: none;">
                                        <button @click="fullscreen = false"
                                            class="absolute top-4 right-4 z-[70] p-2.5 bg-white/10 backdrop-blur-md rounded-full hover:bg-white/25 transition-all">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <iframe
                                            :src="`/3d-engine/index.html?type=concept&id=${selectedConcept?.id}`"
                                            class="w-full h-full border-0"
                                            allowfullscreen
                                            title="3D Model Viewer - Fullscreen">
                                        </iframe>
                                    </div>
                                    <!-- Fullscreen button -->
                                    <button @click="fullscreen = true"
                                        class="absolute top-3 right-3 z-10 p-2 bg-white/80 backdrop-blur-sm rounded-lg shadow-md hover:bg-white transition-all">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Reel -->
                            <div x-show="selectedConcept?.has_reel">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Reel Vidéo</h4>
                                <div class="flex items-center gap-2 text-teal-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium">Vidéo disponible</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="useConcept()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-base font-medium text-white hover:from-teal-600 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Utiliser ce concept
                        </button>
                        <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function conceptSelector() {
            return {
                searchQuery: '',
                filteredCount: {{ $concepts->count() }},
                totalCount: {{ $concepts->count() }},
                modalOpen: false,
                loading: false,
                selectedConcept: null,

                filterConcepts() {
                    const query = this.searchQuery.toLowerCase().trim();
                    const cards = document.querySelectorAll('.concept-card');
                    let visible = 0;

                    cards.forEach(card => {
                        const name = card.dataset.conceptName;
                        const category = card.dataset.conceptCategory;
                        const description = card.dataset.conceptDescription;

                        if (!query || name.includes(query) || category.includes(query) || description.includes(query)) {
                            card.style.display = '';
                            visible++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    this.filteredCount = visible;
                },

                async openModal(conceptId) {
                    this.modalOpen = true;
                    this.loading = true;
                    this.selectedConcept = null;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const response = await fetch(`/admin/concepts/${conceptId}/details`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Erreur lors du chargement des détails');
                        }

                        this.selectedConcept = await response.json();
                    } catch (error) {
                        console.error('Error loading concept:', error);
                        alert('Erreur lors du chargement des détails du concept');
                        this.closeModal();
                    } finally {
                        this.loading = false;
                    }
                },

                closeModal() {
                    this.modalOpen = false;
                    this.selectedConcept = null;
                },

                useConcept() {
                    if (this.selectedConcept && this.selectedConcept.id) {
                        window.location.href = '{{ url('/admin/products/create') }}?concept_id=' + this.selectedConcept.id;
                    }
                }
            };
        }
    </script>
</x-admin-layout>
