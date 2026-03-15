<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                    Détails du concept: {{ $concept->name }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.library-concepts.customize', $concept) }}" class="flex items-center gap-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-6 py-3 rounded-xl font-bold hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    Textures
                </a>
                <a href="{{ route('admin.library-concepts.edit', $concept) }}" class="flex items-center gap-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-6 py-3 rounded-xl font-bold hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('admin.library-concepts.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
                        <div class="p-6">
                            <div class="flex items-start gap-6 mb-6">
                                @if($concept->photos->count() > 0)
                                    <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-teal-100 to-cyan-100 overflow-hidden flex-shrink-0">
                                        <img src="{{ $concept->photos->first()->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-teal-200 to-cyan-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-16 h-16 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $concept->name }}</h1>
                                    @if($concept->category)
                                        <span class="inline-block px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-semibold mb-3">{{ $concept->category->name }}</span>
                                    @endif
                                    <div class="flex items-center gap-4 mt-4">
                                        <div>
                                            <span class="text-sm text-gray-500">Type</span>
                                            <p class="text-3xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                                                Concept
                                            </p>
                                        </div>
                                        <div>
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
                            </div>
                            @if($concept->description)
                                <div class="border-t border-teal-100 pt-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                        </svg>
                                        Description
                                    </h3>
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $concept->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($concept->photos->count() > 0)
                    <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Photos ({{ $concept->photos->count() }})
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($concept->photos as $photo)
                                    <div class="relative group overflow-hidden rounded-xl bg-gradient-to-br from-teal-100 to-cyan-100">
                                        <img src="{{ $photo->url }}" alt="{{ $concept->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer" onclick="openImageModal('{{ $photo->url }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($concept->threedmodels)
                    <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Modèle 3D Viewer
                            </h3>
                            <x-3d-viewer-original 
                                model-type="concept" 
                                :model-id="$concept->id"
                                height="600px"
                            />
                        </div>
                    </div>
                    @endif

                    @if($concept->reel)
                        <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Reel du concept
                                </h3>
                                <div class="rounded-xl overflow-hidden bg-black">
                                    <video controls class="w-full h-auto">
                                        <source src="{{ $concept->reel }}" type="video/mp4">
                                    </video>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Spécifications
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Type</p>
                                    <p class="font-semibold text-gray-900">Concept</p>
                                </div>
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
                                            <p class="text-sm text-gray-500 mb-1">Taille de mesure</p>
                                            <p class="font-semibold text-gray-900">{{ $concept->measure->size }}</p>
                                        </div>
                                    @endif
                                @endif
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Redimensionnable</p>
                                    <p class="font-semibold text-gray-900">{{ $concept->is_resizable ? 'Oui' : 'Non' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100 sticky top-24">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informations
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">ID</p>
                                    <p class="text-sm font-semibold text-gray-900">#{{ $concept->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Source</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        <span class="px-3 py-1 bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 rounded-full text-xs font-semibold">Library</span>
                                    </p>
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
    </div>

    <div id="imageModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
    </div>

    <script>
        function openImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    </script>
</x-admin-layout>
