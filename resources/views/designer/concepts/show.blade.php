<x-designer-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Concept: {{ $concept->name }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('designer.concepts.customize', $concept) }}" class="flex items-center gap-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-6 py-3 rounded-xl font-bold hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    Personnalisation
                </a>
                <a href="{{ route('designer.concepts.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <script>
        function conceptShowEditor() {
            return {
                activeModal: null,
                errorMessage: '',
                successMessage: '',

                openEditModal(section) {
                    this.activeModal = section;
                    this.errorMessage = '';
                },

                closeModal() {
                    this.activeModal = null;
                    this.errorMessage = '';
                },

                async submitSection(section, formData) {
                    this.errorMessage = '';
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const response = await fetch(`/designer/concepts/{{ $concept->id }}/update-${section}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();
                        
                        if (!response.ok || !result.success) {
                            throw new Error(result.message || 'Erreur lors de la mise à jour');
                        }

                        // Reload page to show updated data
                        window.location.reload();
                    } catch (error) {
                        this.errorMessage = error.message;
                    }
                }
            };
        }
    </script>

    <div class="py-12" x-data="conceptShowEditor()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            <div x-show="errorMessage" x-cloak class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <p x-text="errorMessage"></p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information Section -->
                    <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Informations de base</h3>
                                <button @click="openEditModal('basic')" class="flex items-center gap-2 px-4 py-2 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier
                                </button>
                            </div>
                            <div class="flex items-start gap-6 mb-6">
                                @if($concept->photos->count() > 0)
                                    <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-purple-100 to-indigo-100 overflow-hidden flex-shrink-0">
                                        <img src="{{ $concept->photos->first()->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-16 h-16 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $concept->name }}</h1>
                                    @if($concept->category)
                                        <span class="inline-block px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-semibold mb-3">{{ $concept->category->name }}</span>
                                    @endif
                                    <div class="mt-4">
                                        <span class="text-sm text-gray-500">Statut</span>
                                        <div class="mt-1">
                                            @if($concept->status === 'active')
                                                <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-sm font-semibold">Actif</span>
                                            @else
                                                <span class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-sm font-semibold">Inactif</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($concept->description)
                                <div class="border-t border-purple-100 pt-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-3">Description</h3>
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $concept->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Photos Section -->
                    <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Photos ({{ $concept->photos->count() }})</h3>
                                <button @click="openEditModal('photos')" class="flex items-center gap-2 px-4 py-2 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Gérer
                                </button>
                            </div>
                            @if($concept->photos->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($concept->photos as $photo)
                                        <div class="relative group overflow-hidden rounded-xl bg-gradient-to-br from-purple-100 to-indigo-100">
                                            <img src="{{ $photo->url }}" alt="{{ $concept->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">Aucune photo</p>
                            @endif
                        </div>
                    </div>

                    <!-- 3D Model Section -->
                    <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Modèle 3D</h3>
                                <button @click="openEditModal('model')" class="flex items-center gap-2 px-4 py-2 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Gérer
                                </button>
                            </div>
                            @if($concept->threedmodels)
                                <div class="bg-indigo-50 border-2 border-indigo-200 rounded-xl p-4 flex items-center gap-3">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $concept->threedmodels->name }}</p>
                                        <a href="{{ $concept->threedmodels->url }}" download class="text-sm text-indigo-600 hover:underline">Télécharger</a>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">Aucun modèle 3D</p>
                            @endif
                        </div>
                    </div>

                    <!-- Reel Section -->
                    @if($concept->reel)
                        <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold text-gray-900">Reel</h3>
                                    <button @click="openEditModal('reel')" class="flex items-center gap-2 px-4 py-2 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Gérer
                                    </button>
                                </div>
                                <div class="rounded-xl overflow-hidden bg-black">
                                    <video controls class="w-full h-auto">
                                        <source src="{{ $concept->reel }}" type="video/mp4">
                                    </video>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Specifications Section -->
                    <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Spécifications</h3>
                                <button @click="openEditModal('specifications')" class="flex items-center gap-2 px-4 py-2 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($concept->rooms->count() > 0)
                                    <div>
                                        <p class="text-sm text-gray-500 mb-2">Pièces</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($concept->rooms as $room)
                                                <span class="px-3 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 rounded-full text-sm font-medium">{{ $room->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if($concept->metals->count() > 0)
                                    <div>
                                        <p class="text-sm text-gray-500 mb-2">Métaux</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($concept->metals as $metal)
                                                <span class="px-3 py-1 bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 rounded-full text-sm font-medium">{{ $metal->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if($concept->measure)
                                    @if($concept->measure->dimension)
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">Dimensions</p>
                                            <p class="font-semibold text-gray-900">
                                                {{ $concept->measure->dimension->length }} × {{ $concept->measure->dimension->width }} × {{ $concept->measure->dimension->height }} {{ $concept->measure->dimension->unit ?? '' }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($concept->measure->weight)
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">Poids</p>
                                            <p class="font-semibold text-gray-900">
                                                {{ $concept->measure->weight->weight_value }} {{ $concept->measure->weight->weight_unit }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($concept->measure->size)
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">Taille</p>
                                            <p class="font-semibold text-gray-900">{{ $concept->measure->size }}</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100 sticky top-24">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informations</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">ID</p>
                                    <p class="text-sm font-semibold text-gray-900">#{{ $concept->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Créé le</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $concept->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Mis à jour le</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $concept->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modals -->
        @include('designer.concepts.partials.edit-basic-modal')
        @include('designer.concepts.partials.edit-photos-modal')
        @include('designer.concepts.partials.edit-model-modal')
        @include('designer.concepts.partials.edit-reel-modal')
        @include('designer.concepts.partials.edit-specifications-modal')
    </div>
</x-designer-layout>
