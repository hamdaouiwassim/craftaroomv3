<x-constructor-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                Modifier le produit: {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
            <div class="p-8">
                <div class="max-w-6xl mx-auto">
                    @include('admin.inc.messages')

                    <form action="{{ route('constructor.products.update', $product) }}" method="POST" class="mt-4"
                          enctype="multipart/form-data" id="product-form"
                          data-route-prefix="constructor">
                        @csrf
                        @method('PUT')

                        <!-- Step 1: Basic Information -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-lg">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">Informations de base</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Nom du produit *
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all bg-white">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="category_id" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Catégorie *
                                    </label>
                                    <select name="category_id" id="category_id" required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="price" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Prix *
                                    </label>
                                    <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $product->price) }}" required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    @error('price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="currency" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                        </svg>
                                        Devise *
                                    </label>
                                    <select name="currency" id="currency" required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        <option value="USD" {{ old('currency', $product->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="EUR" {{ old('currency', $product->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="MAD" {{ old('currency', $product->currency) == 'MAD' ? 'selected' : '' }}>MAD (د.م)</option>
                                    </select>
                                    @error('currency')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="size" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                        </svg>
                                        Taille *
                                    </label>
                                    <input type="text" name="size" id="size" value="{{ old('size', $product->size) }}" required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                    @error('size')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="status" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Statut *
                                    </label>
                                    <select name="status" id="status" required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mt-6">
                                <label for="description" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    Description *
                                </label>
                                <textarea name="description" id="description" rows="4" required
                                    class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 2: Rooms and Metals -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">Pièces et Métaux</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="rooms" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Pièces *
                                    </label>
                                    <select name="rooms[]" id="rooms" multiple required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ in_array($room->id, old('rooms', $product->rooms->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $room->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rooms')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                    <label for="metals" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        Métaux *
                                    </label>
                                    <select name="metals[]" id="metals" multiple required
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        @foreach($metals as $metal)
                                            <option value="{{ $metal->id }}" {{ in_array($metal->id, old('metals', $product->metals->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $metal->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('metals')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 2.5: Measures -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">Mesures</h3>
                            </div>
                            
                            <div class="p-6 border-2 border-amber-200 rounded-xl bg-gradient-to-br from-amber-50/50 to-orange-50/50 backdrop-blur-sm shadow-sm">
                                <div class="mb-4">
                                    <label for="measure_size" class="block text-sm font-medium text-gray-700">Taille de mesure</label>
                                    <select name="measure_size" id="measure_size"
                                        class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        <option value="">Sélectionner une taille</option>
                                        <option value="SMALL" {{ old('measure_size', $product->measure->size ?? '') == 'SMALL' ? 'selected' : '' }}>Petit (SMALL)</option>
                                        <option value="MEDIUM" {{ old('measure_size', $product->measure->size ?? '') == 'MEDIUM' ? 'selected' : '' }}>Moyen (MEDIUM)</option>
                                        <option value="LARGE" {{ old('measure_size', $product->measure->size ?? '') == 'LARGE' ? 'selected' : '' }}>Grand (LARGE)</option>
                                    </select>
                                    @error('measure_size')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="length" class="block text-sm font-medium text-gray-700">Longueur</label>
                                        <input type="number" step="0.01" name="length" id="length" value="{{ old('length', $product->measure->dimension->length ?? '') }}"
                                            class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        @error('length')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="width" class="block text-sm font-medium text-gray-700">Largeur</label>
                                        <input type="number" step="0.01" name="width" id="width" value="{{ old('width', $product->measure->dimension->width ?? '') }}"
                                            class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        @error('width')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="height" class="block text-sm font-medium text-gray-700">Hauteur</label>
                                        <input type="number" step="0.01" name="height" id="height" value="{{ old('height', $product->measure->dimension->height ?? '') }}"
                                            class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        @error('height')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unité</label>
                                        <select name="unit" id="unit"
                                            class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                            <option value="">Sélectionner une unité</option>
                                            <option value="CM" {{ old('unit', $product->measure->dimension->unit ?? '') == 'CM' ? 'selected' : '' }}>CM (Centimètres)</option>
                                            <option value="FT" {{ old('unit', $product->measure->dimension->unit ?? '') == 'FT' ? 'selected' : '' }}>FT (Pieds)</option>
                                            <option value="INCH" {{ old('unit', $product->measure->dimension->unit ?? '') == 'INCH' ? 'selected' : '' }}>INCH (Pouces)</option>
                                        </select>
                                        @error('unit')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="weight_value" class="block text-sm font-medium text-gray-700">Valeur du poids</label>
                                        <input type="number" step="0.01" name="weight_value" id="weight_value" value="{{ old('weight_value', $product->measure->weight->weight_value ?? '') }}"
                                            class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                        @error('weight_value')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="weight_unit" class="block text-sm font-medium text-gray-700">Unité de poids</label>
                                        <select name="weight_unit" id="weight_unit"
                                            class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                            <option value="">Sélectionner une unité</option>
                                            <option value="KG" {{ old('weight_unit', $product->measure->weight->weight_unit ?? '') == 'KG' ? 'selected' : '' }}>KG (Kilogrammes)</option>
                                            <option value="LB" {{ old('weight_unit', $product->measure->weight->weight_unit ?? '') == 'LB' ? 'selected' : '' }}>LB (Livres)</option>
                                        </select>
                                        @error('weight_unit')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Media Files -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Médias</h3>
                            </div>

                            <!-- Current Photos -->
                            @if($product->photos->count() > 0)
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mb-6"
                                     x-data="{ 
                                        photos: {{ $product->photos->pluck('id')->toJson() }},
                                        deletePhoto(photoId) {
                                            if (!confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')) {
                                                return;
                                            }
                                            
                                            fetch(`/constructor/products/{{ $product->id }}/photos/${photoId}`, {
                                                method: 'DELETE',
                                                headers: {
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                                                    'Accept': 'application/json',
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    this.photos = this.photos.filter(id => id !== photoId);
                                                    alert('✅ Photo supprimée avec succès !');
                                                } else {
                                                    alert('❌ Erreur: ' + (data.message || 'Impossible de supprimer la photo'));
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('❌ Erreur lors de la suppression de la photo');
                                            });
                                        },
                                        validatePhotos() {
                                            const remainingPhotos = this.photos.length;
                                            const newPhotosInput = document.getElementById('photos');
                                            const newPhotosCount = newPhotosInput && newPhotosInput.files ? newPhotosInput.files.length : 0;
                                            
                                            if (remainingPhotos === 0 && newPhotosCount === 0) {
                                                alert('❌ Erreur: Le produit doit avoir au moins une photo.\n\nVous avez supprimé toutes les photos existantes. Veuillez télécharger au moins une nouvelle photo.');
                                                return false;
                                            }
                                            return true;
                                        }
                                     }"
                                     x-ref="photoSection">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-4">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Photos actuelles (<span x-text="photos.length"></span>)
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($product->photos as $photo)
                                            <template x-if="photos.includes({{ $photo->id }})">
                                                <div class="relative group">
                                                    <img src="{{ $photo->url }}" alt="Photo" class="w-full h-32 object-cover rounded-lg">
                                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                                        <button 
                                                            type="button"
                                                            @click="deletePhoto({{ $photo->id }})"
                                                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Supprimer
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">💡 Survolez une photo pour afficher le bouton de suppression</p>
                                </div>
                            @endif

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label for="photos" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    @if($product->photos->count() > 0)
                                        Nouvelles photos (optionnel)
                                    @else
                                        Nouvelles photos (au moins une photo requise) *
                                    @endif
                                </label>
                                <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                    class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-teal-500 file:to-cyan-500 file:text-white hover:file:from-teal-600 hover:file:to-cyan-600">
                                @error('photos')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current 3D Model -->
                            @if($product->threedmodels)
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mt-6">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-4">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        Modèle 3D actuel
                                    </label>
                                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border-2 border-dashed border-indigo-200 mb-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $product->threedmodels->name }}</p>
                                                    <a href="{{ $product->threedmodels->url }}" download class="text-sm text-indigo-600 hover:text-indigo-700 flex items-center gap-1 mt-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        Télécharger
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Téléchargez un nouveau modèle ci-dessous pour remplacer le modèle actuel</p>
                                </div>
                            @endif

                            <!-- New 3D Model Upload -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mt-6">
                                <label for="folderModel" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Nouveau modèle 3D (ZIP) {{ $product->threedmodels ? '(optionnel)' : '' }}
                                </label>
                                <input type="file" name="folderModel" id="folderModel" accept=".zip,application/zip"
                                    class="mt-1 block w-full border-2 border-teal-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-indigo-500 file:to-purple-500 file:text-white hover:file:from-indigo-600 hover:file:to-purple-600">
                                <p class="text-xs text-gray-500 mt-2">Format accepté: ZIP (max 50MB)</p>
                                @error('folderModel')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($product->reel)
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mt-6">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-4">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Reel actuel
                                    </label>
                                    <video controls class="w-full rounded-lg max-w-md">
                                        <source src="{{ $product->reel }}" type="video/mp4">
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

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-teal-100">
                            <a href="{{ route('constructor.products.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                                Annuler
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 text-white rounded-xl font-bold hover:from-teal-600 hover:via-cyan-600 hover:to-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                Mettre à jour le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Select2 for rooms and metals
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                $('#rooms').select2({
                    placeholder: 'Sélectionner des pièces',
                    allowClear: true,
                    width: '100%'
                });
                
                $('#metals').select2({
                    placeholder: 'Sélectionner des métaux',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Store original product values for comparison
            const originalValues = {
                name: document.getElementById('name').value,
                price: document.getElementById('price').value,
                size: document.getElementById('size').value,
                category_id: document.getElementById('category_id').value,
                description: document.getElementById('description').value,
                currency: document.getElementById('currency').value,
                status: document.getElementById('status').value,
                rooms: Array.from(document.getElementById('rooms').selectedOptions).map(opt => opt.value).sort(),
                metals: Array.from(document.getElementById('metals').selectedOptions).map(opt => opt.value).sort(),
                measure_size: document.getElementById('measure_size')?.value || '',
                length: document.getElementById('length')?.value || '',
                width: document.getElementById('width')?.value || '',
                height: document.getElementById('height')?.value || '',
                unit: document.getElementById('unit')?.value || '',
                weight_value: document.getElementById('weight_value')?.value || '',
                weight_unit: document.getElementById('weight_unit')?.value || '',
            };

            // Function to get updated fields
            function getUpdatedFields() {
                const updatedFields = [];
                
                // Get current form values
                const currentValues = {
                    name: document.getElementById('name').value,
                    price: document.getElementById('price').value,
                    size: document.getElementById('size').value,
                    category_id: document.getElementById('category_id').value,
                    description: document.getElementById('description').value,
                    currency: document.getElementById('currency').value,
                    status: document.getElementById('status').value,
                    rooms: Array.from(document.getElementById('rooms').selectedOptions).map(opt => opt.value).sort(),
                    metals: Array.from(document.getElementById('metals').selectedOptions).map(opt => opt.value).sort(),
                    measure_size: document.getElementById('measure_size')?.value || '',
                    length: document.getElementById('length')?.value || '',
                    width: document.getElementById('width')?.value || '',
                    height: document.getElementById('height')?.value || '',
                    unit: document.getElementById('unit')?.value || '',
                    weight_value: document.getElementById('weight_value')?.value || '',
                    weight_unit: document.getElementById('weight_unit')?.value || '',
                };

                // Field labels mapping
                const fieldLabels = {
                    name: 'Nom du produit',
                    price: 'Prix',
                    size: 'Taille',
                    category_id: 'Catégorie',
                    description: 'Description',
                    currency: 'Devise',
                    status: 'Statut',
                    rooms: 'Pièces',
                    metals: 'Métaux',
                    measure_size: 'Taille de mesure',
                    length: 'Longueur',
                    width: 'Largeur',
                    height: 'Hauteur',
                    unit: 'Unité de dimension',
                    weight_value: 'Valeur du poids',
                    weight_unit: 'Unité de poids',
                };

                // Compare values
                Object.keys(currentValues).forEach(key => {
                    if (key === 'rooms' || key === 'metals') {
                        // Compare arrays
                        const original = JSON.stringify(originalValues[key]);
                        const current = JSON.stringify(currentValues[key]);
                        if (original !== current) {
                            const originalLabels = getSelectedLabels(key, originalValues[key]);
                            const currentLabels = getSelectedLabels(key, currentValues[key]);
                            updatedFields.push({
                                field: fieldLabels[key],
                                old: originalLabels,
                                new: currentLabels
                            });
                        }
                    } else {
                        // Compare simple values
                        if (originalValues[key] !== currentValues[key]) {
                            updatedFields.push({
                                field: fieldLabels[key],
                                old: originalValues[key] || '(vide)',
                                new: currentValues[key] || '(vide)'
                            });
                        }
                    }
                });

                // Check for file uploads
                const photosInput = document.getElementById('photos');
                if (photosInput && photosInput.files.length > 0) {
                    updatedFields.push({
                        field: 'Photos',
                        old: '(aucune nouvelle photo)',
                        new: `${photosInput.files.length} nouvelle(s) photo(s)`
                    });
                }

                const folderModelInput = document.getElementById('folderModel');
                if (folderModelInput && folderModelInput.files.length > 0) {
                    updatedFields.push({
                        field: 'Modèle 3D',
                        old: '(ancien modèle)',
                        new: folderModelInput.files[0].name
                    });
                }

                return updatedFields;
            }

            // Helper function to get labels for selected options
            function getSelectedLabels(fieldName, selectedIds) {
                const selectElement = document.getElementById(fieldName);
                if (!selectElement) return '';
                
                return Array.from(selectElement.options)
                    .filter(opt => selectedIds.includes(opt.value))
                    .map(opt => opt.text)
                    .join(', ');
            }

            // Handle form submission with separate reel and model upload
            const form = document.getElementById('product-form');
            const reelInput = document.getElementById('reel');
            const folderModelInput = document.getElementById('folderModel');
            const submitButton = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Validate photos first
                const photoSection = document.querySelector('[x-ref="photoSection"]');
                if (photoSection) {
                    const alpineData = Alpine.$data(photoSection);
                    if (alpineData && !alpineData.validatePhotos()) {
                        return; // Stop form submission if validation fails
                    }
                }
                
                const photosInput = document.getElementById('photos');
                const photosFiles = photosInput?.files;
                const hasPhotos = photosFiles && photosFiles.length > 0;
                
                const reelFile = reelInput?.files[0];
                const hasReel = reelFile && reelFile.size > 0;
                
                const modelFile = folderModelInput?.files[0];
                const hasModel = modelFile && modelFile.size > 0;
                
                console.log('=== Product Update Form Submission ===');
                console.log('Has reel file:', hasReel);
                if (hasReel) {
                    console.log('Reel file info:', {
                        name: reelFile.name,
                        size: reelFile.size,
                        sizeMB: (reelFile.size / 1024 / 1024).toFixed(2) + ' MB',
                        type: reelFile.type
                    });
                }

                // Disable submit button
                submitButton.disabled = true;
                submitButton.textContent = 'Mise à jour en cours...';

                try {
                    // Step 1: Submit form without reel and model
                    const formData = new FormData(form);
                    if (hasReel) {
                        formData.delete('reel'); // Remove reel from form data
                    }
                    if (hasModel) {
                        formData.delete('folderModel'); // Remove model from form data
                    }

                    console.log('Step 1: Submitting product data (without reel)...');
                    const updateResponse = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    // Check response content type
                    const contentType = updateResponse.headers.get('content-type');
                    console.log('Update response content-type:', contentType);
                    console.log('Update response status:', updateResponse.status);

                    // Handle JSON responses
                    if (contentType && contentType.includes('application/json')) {
                        const responseData = await updateResponse.json();
                        
                        if (!updateResponse.ok || !responseData.success) {
                            console.error('Product update error:', responseData);
                            let errorMessage = responseData.message || 'Erreur inconnue';
                            if (responseData.errors) {
                                const errorList = Object.values(responseData.errors).flat().join(', ');
                                errorMessage += ': ' + errorList;
                            }
                            alert('Erreur lors de la mise à jour: ' + errorMessage);
                            submitButton.disabled = false;
                            submitButton.textContent = 'Mettre à jour le produit';
                            return;
                        }
                        
                        console.log('Step 1: Product updated successfully', responseData);
                    } else if (!updateResponse.ok) {
                        // Non-JSON error response (shouldn't happen with Accept: application/json)
                        const text = await updateResponse.text();
                        console.error('Non-JSON error response:', text);
                        alert('Erreur lors de la mise à jour. Vérifiez la console pour plus de détails.');
                        submitButton.disabled = false;
                        submitButton.textContent = 'Mettre à jour le produit';
                        return;
                    } else {
                        // Redirect response (traditional form submission)
                        console.log('Step 1: Product updated successfully (redirect response)');
                    }

                    // Get updated fields before file uploads (will be updated with file uploads later)
                    let updatedFields = getUpdatedFields();

                    // Step 2: Upload photos separately if provided
                    if (hasPhotos) {
                        console.log('Step 2: Uploading photos separately...');
                        try {
                            const photosFormData = new FormData();
                            for (let i = 0; i < photosFiles.length; i++) {
                                photosFormData.append('photos[]', photosFiles[i]);
                            }

                            const productId = {{ $product->id }};
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                            const routePrefix = form.dataset.routePrefix || 'admin';

                            console.log('Uploading photos to:', `/${routePrefix}/products/${productId}/photos`);
                            console.log('Number of photos:', photosFiles.length);

                            const photosResponse = await fetch(`/${routePrefix}/products/${productId}/photos`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: photosFormData
                            });

                            console.log('Photos upload response status:', photosResponse.status);

                            const photosContentType = photosResponse.headers.get('content-type');
                            if (!photosContentType || !photosContentType.includes('application/json')) {
                                const text = await photosResponse.text();
                                console.error('Non-JSON response from photos upload:', text);
                                alert('Erreur lors de l\'upload des photos: Le serveur a retourné une réponse inattendue.');
                                submitButton.disabled = false;
                                submitButton.textContent = 'Mettre à jour le produit';
                                return;
                            }

                            const photosResult = await photosResponse.json();
                            console.log('Photos upload result:', photosResult);

                            if (!photosResponse.ok || !photosResult.success) {
                                let errorMessage = photosResult.message || 'Erreur lors de l\'upload des photos';
                                if (photosResult.errors) {
                                    const errorList = Object.values(photosResult.errors).flat().join(', ');
                                    errorMessage += ': ' + errorList;
                                }
                                console.error('Photos upload failed:', errorMessage);
                                alert('Le produit a été mis à jour, mais l\'upload des photos a échoué:\n\n' + errorMessage);
                                submitButton.disabled = false;
                                submitButton.textContent = 'Mettre à jour le produit';
                                return;
                            }

                            console.log('Step 2: Photos uploaded successfully');
                            
                            updatedFields.push({
                                field: 'Photos',
                                old: '',
                                new: `${photosFiles.length} nouvelle(s) photo(s) ajoutée(s)`
                            });
                        } catch (photosError) {
                            console.error('Photos upload exception:', photosError);
                            console.error('Error stack:', photosError.stack);
                            alert('Le produit a été mis à jour, mais une erreur est survenue lors de l\'upload des photos: ' + photosError.message);
                            submitButton.disabled = false;
                            submitButton.textContent = 'Mettre à jour le produit';
                            return;
                        }
                    }

                    // Step 3: Upload reel separately if provided
                    if (hasReel) {
                        console.log('Step 3: Uploading reel separately...');
                        try {
                            const reelFormData = new FormData();
                            reelFormData.append('reel', reelFile);

                            const productId = {{ $product->id }};
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                            const routePrefix = form.dataset.routePrefix || 'admin';

                            console.log('Uploading reel to:', `/${routePrefix}/products/${productId}/reel`);
                            console.log('Reel file details:', {
                                name: reelFile.name,
                                size: reelFile.size,
                                type: reelFile.type
                            });

                            const reelResponse = await fetch(`/${routePrefix}/products/${productId}/reel`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: reelFormData
                            });

                            console.log('Reel upload response status:', reelResponse.status);
                            console.log('Reel upload response headers:', {
                                'content-type': reelResponse.headers.get('content-type'),
                                'content-length': reelResponse.headers.get('content-length')
                            });

                            // Check if response is JSON
                            const reelContentType = reelResponse.headers.get('content-type');
                            if (!reelContentType || !reelContentType.includes('application/json')) {
                                const text = await reelResponse.text();
                                console.error('Non-JSON response from reel upload:', text);
                                console.error('Response status:', reelResponse.status);
                                alert('Erreur lors de l\'upload du reel: Le serveur a retourné une réponse inattendue. Vérifiez la console pour plus de détails.');
                                submitButton.disabled = false;
                                submitButton.textContent = 'Mettre à jour le produit';
                                return;
                            }

                            const reelResult = await reelResponse.json();
                            console.log('Reel upload result:', reelResult);
                            console.log('Full response details:', {
                                status: reelResponse.status,
                                statusText: reelResponse.statusText,
                                headers: Object.fromEntries(reelResponse.headers.entries()),
                                result: reelResult
                            });

                            if (!reelResponse.ok || !reelResult.success) {
                                let errorMessage = reelResult.message || 'Erreur lors de l\'upload du reel';
                                if (reelResult.errors) {
                                    const errorList = Object.values(reelResult.errors).flat().join(', ');
                                    errorMessage += ': ' + errorList;
                                    console.error('Validation errors:', reelResult.errors);
                                }
                                if (reelResult.debug) {
                                    console.error('=== DEBUG INFO FROM SERVER ===');
                                    console.error('Debug info:', reelResult.debug);
                                    console.error('PHP upload_max_filesize:', reelResult.debug.php_upload_max);
                                    console.error('PHP post_max_size:', reelResult.debug.php_post_max);
                                    console.error('Content-Length header:', reelResult.debug.content_length);
                                    console.error('Has file:', reelResult.debug.has_file);
                                    console.error('All files keys:', reelResult.debug.all_files);
                                    if (reelResult.debug.note) {
                                        console.error('NOTE:', reelResult.debug.note);
                                    }
                                    console.error('==============================');
                                }
                                console.error('Reel upload failed:', errorMessage);
                                
                                // Show detailed error message
                                let detailedMessage = errorMessage;
                                if (reelResult.debug) {
                                    detailedMessage += '\n\nDétails techniques:\n';
                                    detailedMessage += '- Limite PHP upload_max_filesize: ' + (reelResult.debug.php_upload_max || 'N/A') + '\n';
                                    detailedMessage += '- Limite PHP post_max_size: ' + (reelResult.debug.php_post_max || 'N/A') + '\n';
                                    detailedMessage += '- Taille du fichier: ' + (reelFile.size / 1024 / 1024).toFixed(2) + ' MB\n';
                                    if (reelResult.debug.content_length) {
                                        detailedMessage += '- Content-Length: ' + reelResult.debug.content_length + ' bytes\n';
                                    }
                                }
                                
                                alert('Le produit a été mis à jour, mais l\'upload du reel a échoué:\n\n' + detailedMessage + '\n\nVérifiez la console pour plus de détails.');
                                submitButton.disabled = false;
                                submitButton.textContent = 'Mettre à jour le produit';
                                return;
                            }

                            console.log('Step 3: Reel uploaded successfully');
                            
                            // Add reel to updated fields
                            updatedFields.push({
                                field: 'Reel',
                                old: '(ancien reel)',
                                new: reelFile.name
                            });
                        } catch (reelError) {
                            console.error('Reel upload exception:', reelError);
                            console.error('Error stack:', reelError.stack);
                            alert('Le produit a été mis à jour, mais une erreur est survenue lors de l\'upload du reel: ' + reelError.message);
                            submitButton.disabled = false;
                            submitButton.textContent = 'Mettre à jour le produit';
                            return;
                        }
                    }

                    // Step 4: Upload 3D model separately if provided
                    if (hasModel) {
                        console.log('Step 4: Uploading 3D model separately...');
                        try {
                            const modelFormData = new FormData();
                            modelFormData.append('folderModel', modelFile);

                            const productId = {{ $product->id }};
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                            const routePrefix = form.dataset.routePrefix || 'admin';

                            console.log('Uploading model to:', `/${routePrefix}/products/${productId}/model`);
                            console.log('Model file details:', {
                                name: modelFile.name,
                                size: modelFile.size,
                                type: modelFile.type
                            });

                            const modelResponse = await fetch(`/${routePrefix}/products/${productId}/model`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: modelFormData
                            });

                            console.log('Model upload response status:', modelResponse.status);

                            // Check if response is JSON
                            const modelContentType = modelResponse.headers.get('content-type');
                            if (!modelContentType || !modelContentType.includes('application/json')) {
                                const text = await modelResponse.text();
                                console.error('Non-JSON response from model upload:', text);
                                alert('Erreur lors de l\'upload du modèle 3D: Le serveur a retourné une réponse inattendue.');
                                submitButton.disabled = false;
                                submitButton.textContent = 'Mettre à jour le produit';
                                return;
                            }

                            const modelResult = await modelResponse.json();
                            console.log('Model upload result:', modelResult);

                            if (!modelResponse.ok || !modelResult.success) {
                                let errorMessage = modelResult.message || 'Erreur lors de l\'upload du modèle 3D';
                                if (modelResult.errors) {
                                    const errorList = Object.values(modelResult.errors).flat().join(', ');
                                    errorMessage += ': ' + errorList;
                                }
                                console.error('Model upload failed:', errorMessage);
                                alert('Le produit a été mis à jour, mais l\'upload du modèle 3D a échoué:\n\n' + errorMessage);
                                submitButton.disabled = false;
                                submitButton.textContent = 'Mettre à jour le produit';
                                return;
                            }

                            console.log('Step 4: 3D model uploaded successfully');
                            
                            // Add model to updated fields
                            updatedFields.push({
                                field: 'Modèle 3D',
                                old: '(ancien modèle)',
                                new: modelFile.name
                            });
                        } catch (modelError) {
                            console.error('Model upload exception:', modelError);
                            console.error('Error stack:', modelError.stack);
                            alert('Le produit a été mis à jour, mais une erreur est survenue lors de l\'upload du modèle 3D: ' + modelError.message);
                            submitButton.disabled = false;
                            submitButton.textContent = 'Mettre à jour le produit';
                            return;
                        }
                    }

                    // Re-get updated fields to include any file uploads
                    updatedFields = getUpdatedFields();

                    // Success - show alert with updated fields
                    console.log('=== Form submission completed successfully ===');
                    console.log('Updated fields:', updatedFields);
                    
                    // Build alert message
                    let alertMessage = '✅ Produit mis à jour avec succès!\n\n';
                    
                    if (updatedFields.length > 0) {
                        alertMessage += 'Champs mis à jour:\n';
                        alertMessage += '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n';
                        
                        updatedFields.forEach((field, index) => {
                            alertMessage += `${index + 1}. ${field.field}\n`;
                            if (field.old && field.new) {
                                alertMessage += `   Ancien: ${field.old}\n`;
                                alertMessage += `   Nouveau: ${field.new}\n`;
                            } else {
                                alertMessage += `   ${field.new}\n`;
                            }
                            alertMessage += '\n';
                        });
                    } else {
                        alertMessage += 'Aucun champ modifié.\n';
                    }
                    
                    alertMessage += '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n';
                    alertMessage += '\nVous allez être redirigé vers la liste des produits.';
                    
                    // Show alert and then redirect
                    alert(alertMessage);
                    window.location.href = '{{ route("constructor.products.index") }}?success=Product updated successfully';
                } catch (error) {
                    console.error('Form submission error:', error);
                    console.error('Error stack:', error.stack);
                    alert('Erreur lors de la mise à jour: ' + error.message);
                    submitButton.disabled = false;
                    submitButton.textContent = 'Mettre à jour le produit';
                }
            });
        });
    </script>
</x-admin-layout>

