<x-designer-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('designer.concepts.show', $concept) }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                Modifier le concept: {{ $concept->name }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
            <div class="p-8">
                <div class="max-w-6xl mx-auto">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('designer.concepts.update', $concept) }}" method="POST" class="mt-4" id="concept-form"
                          enctype="multipart/form-data" data-route-prefix="designer">
                        @csrf
                        @method('PUT')

                        <!-- Step 1: Basic info -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-lg">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">Informations de base</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom du concept *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $concept->name) }}" required
                                       class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                                <select name="category_id" id="category_id" required class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        @if($category->sub_categories && $category->sub_categories->isNotEmpty())
                                            <optgroup label="{{ $category->name }}">
                                                @foreach($category->sub_categories ?? [] as $sub)
                                                    <option value="{{ $sub->id }}" {{ old('category_id', $concept->category_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $category->id }}" {{ old('category_id', $concept->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm md:col-span-2">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                                <select name="status" id="status" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    <option value="active" {{ old('status', $concept->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status', $concept->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm md:col-span-2">
                                <label class="flex items-center gap-3 text-sm font-semibold text-gray-700">
                                    <input type="checkbox" name="style_type" value="artisant"
                                        {{ old('style_type', $concept->style_type) === 'artisant' ? 'checked' : '' }}
                                        class="h-5 w-5 rounded border-2 border-teal-200 text-teal-600 focus:ring-2 focus:ring-teal-500">
                                    <span>Artisant</span>
                                </label>
                                @error('style_type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm md:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                                <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white resize-none">{{ old('description', $concept->description) }}</textarea>
                                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            </div>
                        </div>

                        <!-- Step 2: Details -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">Pièces et Métaux</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label for="rooms" class="block text-sm font-semibold text-gray-700 mb-2">Pièces *</label>
                                <select name="rooms[]" id="rooms" multiple required class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    @php $selectedRooms = old('rooms', $concept->rooms->pluck('id')->all()); @endphp
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ in_array($room->id, $selectedRooms) ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                @error('rooms')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label for="metals" class="block text-sm font-semibold text-gray-700 mb-2">Métaux *</label>
                                <select name="metals[]" id="metals" multiple required class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    @php $selectedMetals = old('metals', $concept->metals->pluck('id')->all()); @endphp
                                    @foreach($metals as $metal)
                                        <option value="{{ $metal->id }}" {{ in_array($metal->id, $selectedMetals) ? 'selected' : '' }}>{{ $metal->name }}</option>
                                    @endforeach
                                </select>
                                @error('metals')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            </div>
                        </div>

                        <!-- Step 3: Measurements -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-amber-600 to-yellow-600 bg-clip-text text-transparent">Mesures</h3>
                            </div>
                            @php $measure = $concept->measure; $dim = $measure?->dimension; $weight = $measure?->weight; @endphp
                            <div class="p-6 border-2 border-amber-200 rounded-xl bg-gradient-to-br from-amber-50/50 to-orange-50/50 backdrop-blur-sm shadow-sm space-y-4">
                                <div>
                                    <label for="measure_size" class="block text-sm font-medium text-gray-700">Taille de mesure</label>
                                    <select name="measure_size" id="measure_size" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        <option value="">Sélectionner une taille</option>
                                        <option value="SMALL" {{ old('measure_size', $measure?->size) === 'SMALL' ? 'selected' : '' }}>Petit (SMALL)</option>
                                        <option value="MEDIUM" {{ old('measure_size', $measure?->size) === 'MEDIUM' ? 'selected' : '' }}>Moyen (MEDIUM)</option>
                                        <option value="LARGE" {{ old('measure_size', $measure?->size) === 'LARGE' ? 'selected' : '' }}>Grand (LARGE)</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="length" class="block text-sm font-medium text-gray-700">Longueur</label>
                                        <input type="number" step="0.01" name="length" id="length" value="{{ old('length', $dim?->length) }}" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    </div>
                                    <div>
                                        <label for="width" class="block text-sm font-medium text-gray-700">Largeur</label>
                                        <input type="number" step="0.01" name="width" id="width" value="{{ old('width', $dim?->width) }}" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="height" class="block text-sm font-medium text-gray-700">Hauteur</label>
                                        <input type="number" step="0.01" name="height" id="height" value="{{ old('height', $dim?->height) }}" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    </div>
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unité</label>
                                        <select name="unit" id="unit" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                            <option value="">Sélectionner une unité</option>
                                            <option value="CM" {{ old('unit', $dim?->unit ?? 'CM') === 'CM' ? 'selected' : '' }}>CM (Centimètres)</option>
                                            <option value="FT" {{ old('unit', $dim?->unit) === 'FT' ? 'selected' : '' }}>FT (Pieds)</option>
                                            <option value="INCH" {{ old('unit', $dim?->unit) === 'INCH' ? 'selected' : '' }}>INCH (Pouces)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="weight_value" class="block text-sm font-medium text-gray-700">Valeur du poids</label>
                                        <input type="number" step="0.01" name="weight_value" id="weight_value" value="{{ old('weight_value', $weight?->weight_value) }}" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    </div>
                                    <div>
                                        <label for="weight_unit" class="block text-sm font-medium text-gray-700">Unité de poids</label>
                                        <select name="weight_unit" id="weight_unit" class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                            <option value="">Sélectionner une unité</option>
                                            <option value="KG" {{ old('weight_unit', $weight?->weight_unit ?? 'KG') === 'KG' ? 'selected' : '' }}>KG (Kilogrammes)</option>
                                            <option value="LB" {{ old('weight_unit', $weight?->weight_unit) === 'LB' ? 'selected' : '' }}>LB (Livres)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-purple-200">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="is_resizable" id="is_resizable" value="1"
                                            {{ old('is_resizable', $concept->is_resizable ?? false) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-purple-500 shadow-sm focus:ring-purple-300">
                                        <div class="flex-1">
                                            <span class="block text-sm font-semibold text-gray-700 group-hover:text-purple-600 transition-colors">Concept redimensionnable</span>
                                            <span class="block text-xs text-gray-500">Ce concept peut être redimensionné sur demande</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Media -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Médias</h3>
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label for="photos" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Nouvelles photos (optionnel):
                                </label>
                                <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                    class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-teal-500 file:to-cyan-500 file:text-white hover:file:from-teal-600 hover:file:to-cyan-600">
                                @error('photos')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Existing uploaded files with delete --}}
                            @if($concept->photos->count() > 0 || $concept->threedmodels || $concept->reel)
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mb-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Photos actuelles ({{ $concept->photos->count() }})</h4>
                                    @if($concept->photos->count() > 0)
                                        <div class="flex flex-wrap gap-3 mb-4">
                                            @foreach($concept->photos as $photo)
                                                <div class="relative group">
                                                    <img src="{{ $photo->url }}" alt="" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                                    <form action="{{ route('designer.concepts.delete-photo', [$concept, $photo]) }}" method="POST" class="absolute -top-1 -right-1" onsubmit="return confirm('Supprimer cette photo ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1 bg-red-500 hover:bg-red-600 text-white rounded-full shadow" aria-label="Supprimer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">💡 Survolez une photo pour afficher le bouton de suppression</p>
                                    @endif
                                </div>
                            @endif

                            @if($concept->threedmodels)
                                <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl rounded-2xl border border-teal-100 mt-6">
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

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mt-6">
                                <label for="folderModel" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Nouveau modèle 3D (ZIP) (optionnel)
                                </label>
                                <input type="file" name="folderModel" id="folderModel" accept=".zip,application/zip"
                                    class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-indigo-500 file:to-purple-500 file:text-white hover:file:from-indigo-600 hover:file:to-purple-600">
                                <p class="text-xs text-gray-500 mt-2">Format accepté: ZIP (max 50MB)</p>
                                @error('folderModel')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @if($concept->reel)
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mt-6">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-4">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Reel actuel
                                    </label>
                                    <video controls class="w-full rounded-lg max-w-md">
                                        <source src="{{ $concept->reel }}" type="video/mp4">
                                    </video>
                                    <p class="text-xs text-gray-500 mt-2">Téléchargez un nouveau reel pour le remplacer</p>
                                </div>
                            @endif
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mt-6">
                                <label for="reel" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Nouveau reel (optionnel)
                                </label>
                                <input type="file" name="reel" id="reel" accept="video/*"
                                    class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-teal-500 file:to-cyan-500 file:text-white hover:file:from-teal-600 hover:file:to-cyan-600">
                                @error('reel')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="flex flex-wrap items-center justify-between gap-4 mt-10 pt-6 border-t-2 border-teal-200">
                            <a href="{{ route('designer.concepts.show', $concept) }}" class="order-first px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300">
                                Annuler
                            </a>
                            <div class="flex items-center gap-3 order-last">
                                <button type="submit"
                                        class="px-8 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 text-white rounded-xl font-bold hover:from-teal-600 hover:via-cyan-600 hover:to-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    Mettre à jour le concept
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-designer-layout>
