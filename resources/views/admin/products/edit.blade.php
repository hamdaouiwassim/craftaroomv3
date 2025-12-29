<x-admin-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                Modifier le produit: {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
            <div class="p-8">
                <div class="max-w-6xl mx-auto">
                    @include('admin.inc.messages')

                    <form action="{{ route('admin.products.update', $product) }}" method="POST" class="mt-4"
                          enctype="multipart/form-data" id="product-form">
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
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">Informations de base</h3>
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
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm mb-6">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-4">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Photos actuelles
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($product->photos as $photo)
                                            <div class="relative group">
                                                <img src="{{ $photo->url }}" alt="Photo" class="w-full h-32 object-cover rounded-lg">
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-sm">Photo actuelle</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Les nouvelles photos remplaceront les anciennes</p>
                                </div>
                            @endif

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label for="photos" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Nouvelles photos (optionnel)
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
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="delete_3d_model" id="delete_3d_model" value="1" 
                                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        <label for="delete_3d_model" class="text-sm text-gray-700 cursor-pointer">
                                            Supprimer le modèle 3D actuel
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Cochez cette case pour supprimer le modèle actuel, puis téléchargez un nouveau modèle ci-dessous</p>
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
                            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
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
        });
    </script>
</x-admin-layout>

