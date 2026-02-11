<x-constructor-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('constructor.products.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                    {{ $source === 'library' ? 'Concepts de la bibliothèque' : 'Concepts designers' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ selectedConcept: null, showFilters: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-orange-100">
                <div class="p-6">
                    <p class="text-gray-600 mb-6">
                        {{ $source === 'library' ? 'Choisissez un concept de la bibliothèque (ajouté par l\'administration) pour créer un produit. Le formulaire sera pré-rempli avec les informations du concept (modifiables).' : 'Choisissez un concept créé par un designer pour créer un produit. Le formulaire sera pré-rempli avec les informations du concept (modifiables).' }}
                    </p>

                    <!-- Filters Section -->
                    <div class="mb-6">
                        <button @click="showFilters = !showFilters" class="flex items-center gap-2 px-4 py-2 bg-white border-2 border-orange-500 text-orange-600 rounded-lg font-semibold hover:bg-orange-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span x-text="showFilters ? 'Masquer les filtres' : 'Afficher les filtres'">Afficher les filtres</span>
                        </button>

                        <div x-show="showFilters" 
                             x-collapse
                             class="mt-4 bg-white rounded-xl border border-orange-100 p-6">
                            <form method="GET" action="{{ route('constructor.concepts.select') }}" class="space-y-4">
                                <input type="hidden" name="source" value="{{ $source }}">
                                
                                <!-- Search -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rechercher</label>
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Nom ou description..."
                                           class="w-full px-4 py-2 border-2 border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>

                                <!-- Category -->
                                @if($categories->count() > 0)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie</label>
                                        <select name="category" class="w-full px-4 py-2 border-2 border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            <option value="">Toutes les catégories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @if($category->sub_categories->count() > 0)
                                                    @foreach($category->sub_categories as $subCategory)
                                                        <option value="{{ $subCategory->id }}" {{ request('category') == $subCategory->id ? 'selected' : '' }}>
                                                            &nbsp;&nbsp;&nbsp;└─ {{ $subCategory->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Rooms -->
                                @if($rooms->count() > 0)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pièces</label>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                            @foreach($rooms as $room)
                                                <label class="flex items-center gap-2 p-3 bg-orange-50 rounded-lg cursor-pointer hover:bg-orange-100 transition-colors">
                                                    <input type="checkbox" 
                                                           name="rooms[]" 
                                                           value="{{ $room->id }}"
                                                           {{ in_array($room->id, request('rooms', [])) ? 'checked' : '' }}
                                                           class="rounded text-orange-600 focus:ring-orange-500">
                                                    <span class="text-sm text-gray-700">{{ $room->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Metals -->
                                @if($metals->count() > 0)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Métaux</label>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                            @foreach($metals as $metal)
                                                <label class="flex items-center gap-2 p-3 bg-amber-50 rounded-lg cursor-pointer hover:bg-amber-100 transition-colors">
                                                    <input type="checkbox" 
                                                           name="metals[]" 
                                                           value="{{ $metal->id }}"
                                                           {{ in_array($metal->id, request('metals', [])) ? 'checked' : '' }}
                                                           class="rounded text-amber-600 focus:ring-amber-500">
                                                    <span class="text-sm text-gray-700">{{ $metal->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Filter Actions -->
                                <div class="flex items-center gap-3 pt-4">
                                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 transition-all shadow-lg hover:shadow-xl">
                                        Appliquer les filtres
                                    </button>
                                    <a href="{{ route('constructor.concepts.select', ['source' => $source]) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                                        Réinitialiser
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($concepts->count() > 0)
                        <!-- Results Count -->
                        <div class="mb-4 flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold text-orange-600">{{ $concepts->total() }}</span> concept(s) trouvé(s)
                            </p>
                            @if(request()->hasAny(['search', 'category', 'rooms', 'metals']))
                                <a href="{{ route('constructor.concepts.select', ['source' => $source]) }}" class="text-sm text-orange-600 hover:text-orange-700 font-semibold">
                                    Effacer tous les filtres
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($concepts as $concept)
                                <div class="bg-white rounded-xl border-2 border-orange-100 overflow-hidden shadow-sm hover:border-orange-300 hover:shadow-md transition-all">
                                    <div class="aspect-video bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center overflow-hidden">
                                        @if($concept->photos->count() > 0)
                                            <img src="{{ $concept->photos->first()->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-900 truncate">{{ $concept->name }}</h3>
                                        @if($concept->category)
                                            <p class="text-sm text-gray-500 mt-1">{{ $concept->category->name }}</p>
                                        @endif
                                        @if($concept->description)
                                            <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ Str::limit($concept->description, 80) }}</p>
                                        @endif
                                        <div class="mt-4 flex gap-2">
                                            <button @click="selectedConcept = {{ $concept->id }}" class="flex-1 inline-flex items-center gap-2 justify-center px-4 py-2 bg-white border-2 border-orange-500 text-orange-600 rounded-lg font-semibold hover:bg-orange-50 transition-all text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </button>
                                            <a href="{{ route('constructor.products.create', ['concept_id' => $concept->id]) }}" class="flex-1 inline-flex items-center gap-2 justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-lg font-semibold hover:from-orange-600 hover:to-amber-600 transition-all text-sm">
                                                Utiliser
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Concept Detail Modal -->
                                <div x-show="selectedConcept === {{ $concept->id }}" 
                                     x-cloak
                                     @keydown.escape.window="selectedConcept = null"
                                     class="fixed inset-0 z-50 overflow-y-auto"
                                     style="display: none;">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                                        <!-- Background overlay -->
                                        <div class="fixed inset-0 transition-opacity bg-black/50" @click="selectedConcept = null"></div>
                                        
                                        <!-- Modal panel -->
                                        <div class="relative inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                                            <!-- Modal Header -->
                                            <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-2xl font-bold text-white">{{ $concept->name }}</h3>
                                                    <button @click="selectedConcept = null" class="text-white hover:text-gray-200 transition-colors">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @if($concept->category)
                                                    <p class="text-orange-100 text-sm mt-1">{{ $concept->category->name }}</p>
                                                @endif
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                                                <!-- Photos Gallery -->
                                                @if($concept->photos->count() > 0)
                                                    <div class="mb-6">
                                                        <h4 class="text-lg font-bold text-gray-900 mb-3">Photos</h4>
                                                        <div class="grid grid-cols-3 gap-3">
                                                            @foreach($concept->photos->take(6) as $photo)
                                                                <div class="aspect-square rounded-xl overflow-hidden bg-gradient-to-br from-orange-100 to-amber-100">
                                                                    <img src="{{ $photo->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @if($concept->photos->count() > 6)
                                                            <p class="text-sm text-gray-500 mt-2">+{{ $concept->photos->count() - 6 }} more photos</p>
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Description -->
                                                @if($concept->description)
                                                    <div class="mb-6">
                                                        <h4 class="text-lg font-bold text-gray-900 mb-3">Description</h4>
                                                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $concept->description }}</p>
                                                    </div>
                                                @endif

                                                <!-- Specifications -->
                                                <div class="mb-6">
                                                    <h4 class="text-lg font-bold text-gray-900 mb-3">Spécifications</h4>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        @if($concept->rooms->count() > 0)
                                                            <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                                <p class="text-xs text-gray-500 mb-2">Pièces</p>
                                                                <div class="flex flex-wrap gap-2">
                                                                    @foreach($concept->rooms as $room)
                                                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">{{ $room->name }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($concept->metals->count() > 0)
                                                            <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                                <p class="text-xs text-gray-500 mb-2">Métaux</p>
                                                                <div class="flex flex-wrap gap-2">
                                                                    @foreach($concept->metals as $metal)
                                                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">{{ $metal->name }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($concept->measure)
                                                            @if($concept->measure->dimension)
                                                                <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                                    <p class="text-xs text-gray-500 mb-1">Dimensions</p>
                                                                    <p class="font-semibold text-gray-900">
                                                                        {{ $concept->measure->dimension->length }} × 
                                                                        {{ $concept->measure->dimension->width }} × 
                                                                        {{ $concept->measure->dimension->height }} 
                                                                        {{ $concept->measure->dimension->unit }}
                                                                    </p>
                                                                </div>
                                                            @endif

                                                            @if($concept->measure->weight)
                                                                <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                                    <p class="text-xs text-gray-500 mb-1">Poids</p>
                                                                    <p class="font-semibold text-gray-900">
                                                                        {{ $concept->measure->weight->weight_value }} {{ $concept->measure->weight->weight_unit }}
                                                                    </p>
                                                                </div>
                                                            @endif

                                                            @if($concept->measure->size)
                                                                <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                                    <p class="text-xs text-gray-500 mb-1">Taille</p>
                                                                    <p class="font-semibold text-gray-900">{{ $concept->measure->size }}</p>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- 3D Model -->
                                                @if($concept->threedmodels)
                                                    <div class="mb-6">
                                                        <h4 class="text-lg font-bold text-gray-900 mb-3">Modèle 3D</h4>
                                                        <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl flex items-center gap-3">
                                                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                            </svg>
                                                            <div>
                                                                <p class="font-semibold text-gray-900">{{ $concept->threedmodels->name }}</p>
                                                                <a href="{{ $concept->threedmodels->url }}" download class="text-sm text-orange-600 hover:underline">Télécharger</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Video Reel -->
                                                @if($concept->reel)
                                                    <div class="mb-6">
                                                        <h4 class="text-lg font-bold text-gray-900 mb-3">Vidéo</h4>
                                                        <div class="rounded-xl overflow-hidden bg-black">
                                                            <video controls class="w-full h-auto">
                                                                <source src="{{ $concept->reel }}" type="video/mp4">
                                                            </video>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
                                                <button @click="selectedConcept = null" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                                                    Fermer
                                                </button>
                                                <a href="{{ route('constructor.products.create', ['concept_id' => $concept->id]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 transition-all shadow-lg hover:shadow-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Créer un Produit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $concepts->links() }}</div>
                    @else
                        <div class="text-center py-12 bg-white rounded-xl border border-orange-100">
                            <p class="text-gray-600 mb-4">
                                {{ $source === 'library' ? 'Aucun concept dans la bibliothèque pour le moment.' : 'Aucun concept designer disponible.' }}
                            </p>
                            <a href="{{ route('constructor.products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                                Retour aux produits
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</x-constructor-layout>
