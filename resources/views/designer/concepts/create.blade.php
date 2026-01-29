<x-designer-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                Nouveau concept
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
            <div class="p-8">
                <div class="max-w-6xl mx-auto">
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('designer.concepts.store') }}" method="POST" class="mt-4" id="concept-form"
                          enctype="multipart/form-data" data-route-prefix="designer"
                          x-data="conceptForm()"
                          @submit.prevent="currentStep === totalSteps && submitForm()">
                        @csrf

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
                        <div x-show="currentStep === 1" class="space-y-6">
                            <h3 class="text-xl font-bold text-purple-600">Informations de base</h3>
                            <div class="bg-white/60 rounded-xl p-5 border border-purple-100">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom du concept *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                                                <option value="{{ $sub->id }}" {{ old('category_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        @if(!$category->sub_categories || $category->sub_categories->isEmpty())
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-purple-100">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                                <select name="status" id="status" class="mt-1 block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Step 2: Details -->
                        <div x-show="currentStep === 2" class="space-y-6">
                            <h3 class="text-xl font-bold text-indigo-600">Détails du concept</h3>
                            <div class="bg-white/60 rounded-xl p-5 border border-indigo-100">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full border-2 border-indigo-200 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 bg-white resize-none">{{ old('description') }}</textarea>
                                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-indigo-100">
                                <label for="rooms" class="block text-sm font-semibold text-gray-700 mb-2">Pièces (Rooms) *</label>
                                <select name="rooms[]" id="rooms" multiple required class="mt-1 block w-full border-2 border-indigo-200 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 bg-white">
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ in_array($room->id, old('rooms', [])) ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-indigo-600 mt-2">Sélectionnez une ou plusieurs pièces (Ctrl/Cmd pour multi)</p>
                                @error('rooms')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-indigo-100">
                                <label for="metals" class="block text-sm font-semibold text-gray-700 mb-2">Métaux *</label>
                                <select name="metals[]" id="metals" multiple required class="mt-1 block w-full border-2 border-indigo-200 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 bg-white">
                                    @foreach($metals as $metal)
                                        <option value="{{ $metal->id }}" {{ in_array($metal->id, old('metals', [])) ? 'selected' : '' }}>{{ $metal->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-indigo-600 mt-2">Sélectionnez un ou plusieurs métaux (Ctrl/Cmd pour multi)</p>
                                @error('metals')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Step 3: Media -->
                        <div x-show="currentStep === 3" class="space-y-6">
                            <h3 class="text-xl font-bold text-teal-600">Médias</h3>
                            <div class="bg-white/60 rounded-xl p-5 border border-teal-100">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Photos du concept</label>
                                <div id="photos-dropzone" class="dropzone border-2 border-dashed border-teal-300 rounded-xl p-8 text-center min-h-[180px]">
                                    <div class="dz-message">
                                        <p class="text-sm text-gray-700">Glissez-déposez vos photos ici ou cliquez pour sélectionner</p>
                                        <p class="text-xs text-gray-500 mt-1">JPG, PNG jusqu'à 5MB (max 10 fichiers)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-teal-100">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Modèle 3D (ZIP)</label>
                                <div id="model-dropzone" class="dropzone border-2 border-dashed border-indigo-300 rounded-xl p-8 text-center min-h-[180px]">
                                    <div class="dz-message">
                                        <p class="text-sm text-gray-700">Glissez-déposez votre modèle 3D (ZIP) ici</p>
                                        <p class="text-xs text-gray-500 mt-1">Fichier ZIP jusqu'à 50MB</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/60 rounded-xl p-5 border border-teal-100">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Reel (Vidéo) <span class="text-gray-500 font-normal">(Optionnel)</span></label>
                                <div id="reel-dropzone" class="dropzone border-2 border-dashed border-cyan-300 rounded-xl p-8 text-center min-h-[180px]">
                                    <div class="dz-message">
                                        <p class="text-sm text-gray-700">Glissez-déposez votre vidéo ici (Optionnel)</p>
                                        <p class="text-xs text-gray-500 mt-1">Fichier vidéo jusqu'à 100MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Measurements -->
                        <div x-show="currentStep === 4" class="space-y-6">
                            <h3 class="text-xl font-bold text-amber-600">Mesures <span class="text-gray-500 text-base">(au moins une requise)</span></h3>
                            <div class="p-6 border-2 border-amber-200 rounded-xl bg-amber-50/50 space-y-4">
                                <div>
                                    <label for="measure_size" class="block text-sm font-medium text-gray-700">Taille (SMALL / MEDIUM / LARGE)</label>
                                    <select name="measure_size" id="measure_size" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                        <option value="">—</option>
                                        <option value="SMALL" {{ old('measure_size') === 'SMALL' ? 'selected' : '' }}>SMALL</option>
                                        <option value="MEDIUM" {{ old('measure_size') === 'MEDIUM' ? 'selected' : '' }}>MEDIUM</option>
                                        <option value="LARGE" {{ old('measure_size') === 'LARGE' ? 'selected' : '' }}>LARGE</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <label for="length" class="block text-sm font-medium text-gray-700">Longueur</label>
                                        <input type="number" step="0.01" name="length" id="length" value="{{ old('length') }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="width" class="block text-sm font-medium text-gray-700">Largeur</label>
                                        <input type="number" step="0.01" name="width" id="width" value="{{ old('width') }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="height" class="block text-sm font-medium text-gray-700">Hauteur</label>
                                        <input type="number" step="0.01" name="height" id="height" value="{{ old('height') }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unité</label>
                                        <select name="unit" id="unit" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                            <option value="CM" {{ old('unit', 'CM') === 'CM' ? 'selected' : '' }}>CM</option>
                                            <option value="FT" {{ old('unit') === 'FT' ? 'selected' : '' }}>FT</option>
                                            <option value="INCH" {{ old('unit') === 'INCH' ? 'selected' : '' }}>INCH</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="weight_value" class="block text-sm font-medium text-gray-700">Poids (valeur)</label>
                                        <input type="number" step="0.01" name="weight_value" id="weight_value" value="{{ old('weight_value') }}" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                    </div>
                                    <div>
                                        <label for="weight_unit" class="block text-sm font-medium text-gray-700">Unité poids</label>
                                        <select name="weight_unit" id="weight_unit" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-white">
                                            <option value="KG" {{ old('weight_unit', 'KG') === 'KG' ? 'selected' : '' }}>KG</option>
                                            <option value="LB" {{ old('weight_unit') === 'LB' ? 'selected' : '' }}>LB</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Review -->
                        <div x-show="currentStep === 5" x-init="$watch('currentStep', value => { if (value === 5) updateReview(); })" class="space-y-6">
                            <h3 class="text-xl font-bold text-green-600">Vérification</h3>
                            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-6">
                                <p class="text-sm font-semibold text-green-800">Vérifiez les informations avant de créer le concept.</p>
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

                        <!-- Navigation -->
                        <div class="flex justify-between mt-10 pt-6 border-t-2 border-purple-200">
                            <button type="button" @click="prevStep()" x-show="currentStep > 1"
                                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300">
                                Précédent
                            </button>
                            <button type="button" @click="nextStep()" x-show="currentStep < totalSteps"
                                    class="ml-auto px-8 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl font-bold hover:from-purple-600 hover:to-indigo-600">
                                Suivant
                            </button>
                            <button type="button" @click="submitForm()" x-show="currentStep === totalSteps"
                                    class="ml-auto px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold hover:from-green-600 hover:to-emerald-600">
                                Créer le concept
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-designer-layout>
