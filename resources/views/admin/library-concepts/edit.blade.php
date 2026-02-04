<x-admin-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.library-concepts.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Modifier : {{ $concept->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
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

                    <form action="{{ route('admin.library-concepts.update', $concept) }}" method="POST" class="mt-4" id="concept-form"
                          enctype="multipart/form-data" data-route-prefix="admin" data-concept-path="library-concepts"
                          x-data="typeof window.conceptEditForm === 'function' ? window.conceptEditForm() : {}"
                          @submit="if (currentStep !== totalSteps) $event.preventDefault()">
                        @csrf
                        @method('PUT')

                        <!-- In-view validation / error message -->
                        <div x-show="errorMessage" x-cloak
                             class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <p class="flex-1 min-w-0" x-text="errorMessage"></p>
                            <button type="button" @click="errorMessage = ''" class="flex-shrink-0 p-1 rounded hover:bg-red-200 transition-colors" aria-label="Fermer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <!-- Current step indicator -->
                        <p class="text-sm font-medium text-purple-600 mb-2">
                            <span x-text="'Étape ' + currentStep + ' sur ' + totalSteps"></span>
                            <span class="text-gray-500 font-normal" x-text="' — ' + (stepLabels[currentStep] || '')"></span>
                        </p>

                        <!-- Stepper progress -->
                        <div class="mb-10">
                            <div class="flex items-center justify-between mb-4">
                                <template x-for="step in steps" :key="step">
                                    <div class="flex items-center flex-1">
                                        <div class="flex flex-col items-center flex-1">
                                            <div
                                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center font-bold text-base sm:text-lg transition-all duration-300 cursor-pointer shadow-lg"
                                                @click="goToStep(step)"
                                                :class="currentStep >= step ? 'bg-gradient-to-br from-purple-500 via-indigo-500 to-teal-500 text-white' : 'bg-gray-200 text-gray-500'">
                                                <span x-show="currentStep <= step" x-text="step"></span>
                                                <svg x-show="currentStep > step" class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <div class="mt-2 text-xs sm:text-sm text-center font-medium" :class="currentStep >= step ? 'text-purple-600 font-bold' : 'text-gray-500'">
                                                <span x-text="stepLabels[step]"></span>
                                            </div>
                                        </div>
                                        <template x-if="step < totalSteps">
                                            <div class="flex-1 h-2 mx-1 sm:mx-3 rounded-full" :class="currentStep > step ? 'bg-gradient-to-r from-purple-500 to-indigo-500' : 'bg-gray-200'"></div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Step 1: Basic info -->
                        <div x-show="currentStep === 1" x-cloak class="space-y-6">
                            <h3 class="text-xl font-bold text-purple-600">Informations de base</h3>
                            <div class="bg-white/60 rounded-xl p-5 border border-purple-100">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom du concept *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $concept->name) }}" required
                                       class="mt-1 block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-purple-100">
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                                <select name="category_id" id="category_id" required class="mt-1 block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->sub_categories ?? [] as $sub)
                                                <option value="{{ $sub->id }}" {{ old('category_id', $concept->category_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        @if(!$category->sub_categories || $category->sub_categories->isEmpty())
                                            <option value="{{ $category->id }}" {{ old('category_id', $concept->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-purple-100">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                                <select name="status" id="status" class="mt-1 block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                    <option value="active" {{ old('status', $concept->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status', $concept->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Step 2: Details -->
                        <div x-show="currentStep === 2" x-cloak class="space-y-6">
                            <h3 class="text-xl font-bold text-indigo-600">Détails du concept</h3>
                            <div class="bg-white/60 rounded-xl p-5 border border-indigo-100">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full border-2 border-indigo-200 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 bg-white resize-none">{{ old('description', $concept->description) }}</textarea>
                                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-indigo-100">
                                <label for="rooms" class="block text-sm font-semibold text-gray-700 mb-2">Pièces (Rooms) *</label>
                                <select name="rooms[]" id="rooms" multiple required class="mt-1 block w-full border-2 border-indigo-200 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 bg-white">
                                    @php $selectedRooms = old('rooms', $concept->rooms->pluck('id')->all()); @endphp
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ in_array($room->id, $selectedRooms) ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-indigo-600 mt-2">Sélectionnez une ou plusieurs pièces (Ctrl/Cmd pour multi)</p>
                                @error('rooms')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-indigo-100">
                                <label for="metals" class="block text-sm font-semibold text-gray-700 mb-2">Métaux *</label>
                                <select name="metals[]" id="metals" multiple required class="mt-1 block w-full border-2 border-indigo-200 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 bg-white">
                                    @php $selectedMetals = old('metals', $concept->metals->pluck('id')->all()); @endphp
                                    @foreach($metals as $metal)
                                        <option value="{{ $metal->id }}" {{ in_array($metal->id, $selectedMetals) ? 'selected' : '' }}>{{ $metal->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-indigo-600 mt-2">Sélectionnez un ou plusieurs métaux (Ctrl/Cmd pour multi)</p>
                                @error('metals')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Step 3: Media -->
                        <div x-show="currentStep === 3" x-cloak class="space-y-6">
                            <h3 class="text-xl font-bold text-teal-600">Médias</h3>

                            {{-- Existing uploaded files with delete --}}
                            @if($concept->photos->count() > 0 || $concept->threedmodels || $concept->reel)
                                <div class="bg-white/60 rounded-xl p-5 border border-teal-100">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Fichiers actuels</h4>
                                    @if($concept->photos->count() > 0)
                                        <p class="text-xs text-gray-600 mb-2">Photos</p>
                                        <div class="flex flex-wrap gap-3 mb-4">
                                            @foreach($concept->photos as $photo)
                                                <div class="relative group">
                                                    <img src="{{ $photo->url }}" alt="" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                                    <form action="{{ route('admin.library-concepts.delete-photo', [$concept, $photo]) }}" method="POST" class="absolute -top-1 -right-1" onsubmit="return confirm('Supprimer cette photo ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1 bg-red-500 hover:bg-red-600 text-white rounded-full shadow" aria-label="Supprimer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($concept->threedmodels)
                                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                                            <p class="text-sm text-gray-700">Modèle 3D : <span class="font-medium">{{ $concept->threedmodels->name }}</span></p>
                                            <form action="{{ route('admin.library-concepts.delete-model', $concept) }}" method="POST" onsubmit="return confirm('Supprimer le modèle 3D ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Supprimer</button>
                                            </form>
                                        </div>
                                    @endif
                                    @if($concept->reel)
                                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                                            <p class="text-sm text-gray-700">Reel : <a href="{{ $concept->reel }}" target="_blank" rel="noopener" class="text-teal-600 hover:underline">Vidéo</a></p>
                                            <form action="{{ route('admin.library-concepts.delete-reel', $concept) }}" method="POST" onsubmit="return confirm('Supprimer le reel ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Supprimer</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="bg-white/60 rounded-xl p-5 border border-teal-100">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Photos du concept</label>
                                <p class="text-xs text-gray-500 mb-2">Ajouter de nouvelles photos (optionnel)</p>
                                <div id="photos-dropzone" class="dropzone border-2 border-dashed border-teal-300 rounded-xl p-8 text-center min-h-[180px]">
                                    <div class="dz-message">
                                        <p class="text-sm text-gray-700">Glissez-déposez vos photos ici ou cliquez pour sélectionner</p>
                                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, WebP jusqu'à 5MB (max 10 fichiers)</p>
                                    </div>
                                </div>
                                <p x-show="photosError" x-cloak x-text="photosError" class="mt-2 text-sm text-red-600"></p>
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-teal-100">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Modèle 3D (ZIP)</label>
                                <p class="text-xs text-gray-500 mb-2">Ajouter ou remplacer le modèle 3D (optionnel)</p>
                                <div id="model-dropzone" class="dropzone border-2 border-dashed border-indigo-300 rounded-xl p-8 text-center min-h-[180px]">
                                    <div class="dz-message">
                                        <p class="text-sm text-gray-700">Glissez-déposez votre modèle 3D (ZIP) ici</p>
                                        <p class="text-xs text-gray-500 mt-1">Fichier ZIP jusqu'à 50MB</p>
                                    </div>
                                </div>
                                <p x-show="modelError" x-cloak x-text="modelError" class="mt-2 text-sm text-red-600"></p>
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-teal-100">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Reel (Vidéo) <span class="text-gray-500 font-normal">(Optionnel)</span></label>
                                <p class="text-xs text-gray-500 mb-2">Ajouter ou remplacer la vidéo (optionnel)</p>
                                <div id="reel-dropzone" class="dropzone border-2 border-dashed border-teal-300 rounded-xl p-8 text-center min-h-[180px]">
                                    <div class="dz-message">
                                        <p class="text-sm text-gray-700">Glissez-déposez votre vidéo ici (Optionnel)</p>
                                        <p class="text-xs text-gray-500 mt-1">Fichier vidéo jusqu'à 100MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Measurements -->
                        <div x-show="currentStep === 4" x-cloak class="space-y-6">
                            <h3 class="text-xl font-bold text-amber-600">Mesures <span class="text-gray-500 text-base">(au moins une requise)</span></h3>
                            @php $measure = $concept->measure; $dim = $measure?->dimension; $weight = $measure?->weight; @endphp
                            <div class="p-6 border-2 border-amber-200 rounded-xl bg-amber-50/50 space-y-4">
                                <div>
                                    <label for="measure_size" class="block text-sm font-medium text-gray-700">Taille (SMALL / MEDIUM / LARGE)</label>
                                    <select name="measure_size" id="measure_size" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                        <option value="">—</option>
                                        <option value="SMALL" {{ old('measure_size', $measure?->size) === 'SMALL' ? 'selected' : '' }}>SMALL</option>
                                        <option value="MEDIUM" {{ old('measure_size', $measure?->size) === 'MEDIUM' ? 'selected' : '' }}>MEDIUM</option>
                                        <option value="LARGE" {{ old('measure_size', $measure?->size) === 'LARGE' ? 'selected' : '' }}>LARGE</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <label for="length" class="block text-sm font-medium text-gray-700">Longueur</label>
                                        <input type="number" step="0.01" name="length" id="length" value="{{ old('length', $dim?->length) }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="width" class="block text-sm font-medium text-gray-700">Largeur</label>
                                        <input type="number" step="0.01" name="width" id="width" value="{{ old('width', $dim?->width) }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="height" class="block text-sm font-medium text-gray-700">Hauteur</label>
                                        <input type="number" step="0.01" name="height" id="height" value="{{ old('height', $dim?->height) }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unité</label>
                                        <select name="unit" id="unit" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                            <option value="CM" {{ old('unit', $dim?->unit ?? 'CM') === 'CM' ? 'selected' : '' }}>CM</option>
                                            <option value="FT" {{ old('unit', $dim?->unit) === 'FT' ? 'selected' : '' }}>FT</option>
                                            <option value="INCH" {{ old('unit', $dim?->unit) === 'INCH' ? 'selected' : '' }}>INCH</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="weight_value" class="block text-sm font-medium text-gray-700">Poids (valeur)</label>
                                        <input type="number" step="0.01" name="weight_value" id="weight_value" value="{{ old('weight_value', $weight?->weight_value) }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="weight_unit" class="block text-sm font-medium text-gray-700">Unité poids</label>
                                        <select name="weight_unit" id="weight_unit" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                            <option value="KG" {{ old('weight_unit', $weight?->weight_unit ?? 'KG') === 'KG' ? 'selected' : '' }}>KG</option>
                                            <option value="LB" {{ old('weight_unit', $weight?->weight_unit) === 'LB' ? 'selected' : '' }}>LB</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Review -->
                        <div x-show="currentStep === 5" x-cloak class="space-y-6">
                            <h3 class="text-xl font-bold text-green-600">Vérification</h3>
                            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-6">
                                <p class="text-sm font-semibold text-green-800">Vérifiez les informations avant d'enregistrer.</p>
                            </div>
                            <div class="bg-white/60 rounded-xl p-6 border border-green-100 space-y-4">
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-700">Nom</span>
                                    <span id="review-name" class="text-gray-600">-</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-700">Catégorie</span>
                                    <span id="review-category" class="text-gray-600">-</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-700">Description</span>
                                    <span id="review-description" class="text-gray-600 max-w-xs truncate">-</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-700">Pièces</span>
                                    <span id="review-rooms" class="text-gray-600">-</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-700">Métaux</span>
                                    <span id="review-metals" class="text-gray-600">-</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-700">Photos</span>
                                    <span id="review-photos" class="text-gray-600">-</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-700">Modèle 3D</span>
                                    <span id="review-model" class="text-gray-600">-</span>
                                </div>
                                <div class="flex justify-between py-3">
                                    <span class="font-semibold text-gray-700">Mesures</span>
                                    <span id="review-measures" class="text-gray-600">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation: Annuler left; Précédent / Suivant / Enregistrer right -->
                        <div class="flex flex-wrap items-center justify-between gap-4 mt-10 pt-6 border-t-2 border-purple-200">
                            <a href="{{ route('admin.library-concepts.index') }}" class="order-first px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                                Annuler
                            </a>
                            <div class="flex items-center gap-3 order-last">
                                <button type="button" x-show="currentStep > 1"
                                        x-on:click="prevStep()"
                                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                                    Précédent
                                </button>
                                <button type="button" x-show="currentStep < totalSteps"
                                        x-on:click="nextStep()"
                                        class="px-8 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl font-bold hover:from-purple-600 hover:to-indigo-600 transition-colors">
                                    Suivant
                                </button>
                                <button type="submit" x-show="currentStep === totalSteps"
                                        class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold hover:from-green-600 hover:to-emerald-600 transition-colors">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
