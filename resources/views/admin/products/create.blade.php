<x-admin-layout>

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
                Créer un nouvel produit
        </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100 dark:border-gray-700">
            <div class="p-8">
                <div class="max-w-6xl mx-auto">
                    @include('admin.inc.messages')

                    <form action="{{ route('admin.products.store') }}" method="POST" class="mt-4"
                          enctype="multipart/form-data" id="product-form"
                          data-route-prefix="admin"
                          @if(isset($concept) && $concept)
                          x-data="productFormWithConceptData({{ $concept->photos ? json_encode($concept->photos->pluck('id')->toArray()) : '[]' }}, {{ $concept->reel ? 'true' : 'false' }})"
                          @else
                          x-data="productFormData()"
                          @endif
                        @csrf
                        @if(isset($concept) && $concept)
                            <input type="hidden" name="concept_id" value="{{ $concept->id }}">
                        @endif

                        <!-- Enhanced Stepper Progress -->
                        <div class="mb-10">
                            <div class="flex items-center justify-between mb-4">
                                <template x-for="step in totalSteps" :key="step">
                                    <div class="flex items-center flex-1">
                                        <div class="flex flex-col items-center flex-1">
                                            <div 
                                                class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300 cursor-pointer shadow-lg transform hover:scale-110"
                                                @click="goToStep(step)"
                                                :class="currentStep >= step 
                                                    ? 'bg-gradient-to-br from-purple-500 via-indigo-500 to-teal-500 text-white shadow-purple-500/50 ring-4 ring-purple-200' 
                                                    : 'bg-gray-200 text-gray-500 hover:bg-gray-300'">
                                                <template x-if="currentStep > step">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </template>
                                                <span x-show="currentStep <= step" x-text="step"></span>
                                            </div>
                                            <div class="mt-3 text-sm text-center hidden sm:block font-medium" 
                                                 :class="currentStep >= step ? 'text-purple-600 font-bold' : 'text-gray-500'">
                                                <span x-text="stepLabels[step]"></span>
                                            </div>
                                        </div>
                                        <template x-if="step < totalSteps">
                                            <div class="flex-1 h-2 mx-3 rounded-full transition-all duration-300"
                                                 :class="currentStep > step ? 'bg-gradient-to-r from-purple-500 to-indigo-500 shadow-lg' : 'bg-gray-200'">
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Step 1: Basic Information -->
                        <div x-show="currentStep === 1" class="space-y-6 animate-fadeIn">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Informations de base</h3>
                            </div>
                            
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-purple-100 shadow-sm">
                                <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Nom du produit *
                                </label>
                                <input type="text" name="name" id="name" required
                                    value="{{ old('name', optional($concept)->name ?? '') }}"
                                    class="mt-1 block w-full border-2 border-purple-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-white">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-purple-100 shadow-sm">
                                <label for="category_id" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Catégorie *
                                </label>
                                <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full border-2 border-purple-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-white">
                                    <option value="">Sélectionner une catégorie</option>
                                @foreach ($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                                        @foreach ($category->sub_categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id', optional($concept)->category_id ?? null) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                                @error('category_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-purple-100 shadow-sm">
                                <label for="status" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    État
                                </label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full border-2 border-purple-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-white">
                                <option value="active">Actif</option>
                                <option value="inactive">Non actif</option>
                            </select>
                            @error('status')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                            </div>
                        </div>

                        <!-- Step 2: Product Details -->
                        <div x-show="currentStep === 2" 
                             class="space-y-6 animate-fadeIn">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-indigo-100 to-teal-100 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-teal-600 bg-clip-text text-transparent">Détails du produit</h3>
                            </div>
                            
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-indigo-100 shadow-sm">
                                <label for="description" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    Description *
                                </label>
                                <textarea name="description" id="description" rows="5" required
                                    class="mt-1 block w-full border-2 border-indigo-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white resize-none">{{ old('description', optional($concept)->description ?? '') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-indigo-100 shadow-sm">
                                    <label for="price" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Prix *
                                    </label>
                                    <input type="number" step="0.01" name="price" id="price" required
                                        class="mt-1 block w-full border-2 border-indigo-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                                </div>

                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-indigo-100 shadow-sm">
                                    <label for="currency" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Devise *
                                    </label>
                                    <select name="currency" id="currency" required
                                        class="mt-1 block w-full border-2 border-indigo-200 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                                        <option value="">Sélectionner une devise</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->symbol }}">{{ $currency->name }} ({{ $currency->symbol }})</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-indigo-100 shadow-sm">
                                <label for="rooms" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Pièces (Rooms) *
                                </label>
                                <select name="rooms[]" id="rooms" multiple required
                                    class="mt-1 block w-full border-2 border-indigo-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                                    @php $selectedRoomIds = old('rooms', $concept ? $concept->rooms->pluck('id')->all() : []); @endphp
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" {{ in_array($room->id, $selectedRoomIds) ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-indigo-600 mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Sélectionnez une ou plusieurs pièces
                                </p>
                                @error('rooms')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-indigo-100 shadow-sm">
                                <label for="metals" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    Métaux *
                                </label>
                                <select name="metals[]" id="metals" multiple required
                                    class="mt-1 block w-full border-2 border-indigo-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                                    @php $selectedMetalIds = old('metals', $concept ? $concept->metals->pluck('id')->toArray() : []); @endphp
                                    @foreach ($metals as $metal)
                                        <option value="{{ $metal->id }}" {{ in_array($metal->id, $selectedMetalIds) ? 'selected' : '' }}>{{ $metal->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-indigo-600 mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Sélectionnez un ou plusieurs métaux
                                </p>
                                @error('metals')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 3: Media Uploads with Dropzone -->
                        <div x-show="currentStep === 3" class="space-y-6 animate-fadeIn">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-lg">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">Médias</h3>
                            </div>

                            @if($concept && ($concept->photos->count() > 0 || $concept->threedmodels || $concept->reel))
                            <div class="bg-amber-50/80 border-2 border-amber-200 rounded-xl p-5 mb-6">
                                <h4 class="text-sm font-bold text-amber-800 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Médias du concept (référence)
                                </h4>
                                <p class="text-xs text-amber-700 mb-4">
                                    Ces médias proviennent du concept sélectionné. Vous pouvez les supprimer ou ajouter vos propres fichiers.
                                    <span class="font-semibold text-red-600">⚠️ Supprimer un média du concept ne l'affecte pas dans le concept original.</span>
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @if($concept->photos->count() > 0)
                                        <div>
                                            <p class="text-xs font-semibold text-amber-800 mb-2">Photos du concept ({{ $concept->photos->count() }})</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($concept->photos as $photo)
                                                    <template x-if="!conceptPhotosToDelete.includes({{ $photo->id }})">
                                                        <div class="relative group w-20 h-20">
                                                            <a href="{{ $photo->url }}" target="_blank" rel="noopener" class="block w-full h-full rounded-lg overflow-hidden border-2 border-amber-200 hover:border-amber-400 shadow-sm">
                                                                <img src="{{ $photo->url }}" alt="{{ $photo->name }}" class="w-full h-full object-cover">
                                                            </a>
                                                            <button 
                                                                type="button"
                                                                @click="removeConceptPhoto({{ $photo->id }})"
                                                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                            <input type="hidden" name="concept_photos[{{ $photo->id }}]" value="{{ $photo->url }}">
                                                        </div>
                                                    </template>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if($concept->threedmodels)
                                        <template x-if="true">
                                            <div>
                                                <p class="text-xs font-semibold text-amber-800 mb-2">Modèle 3D du concept</p>
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ $concept->threedmodels->url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-amber-100 text-amber-800 rounded-lg text-sm font-medium hover:bg-amber-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                        {{ $concept->threedmodels->name ?? 'Télécharger' }}
                                                    </a>
                                                    <input type="hidden" name="concept_3d_model" value="{{ $concept->threedmodels->url }}">
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Le modèle 3D du concept sera utilisé si vous n'en téléchargez pas un nouveau.</p>
                                            </div>
                                        </template>
                                    @endif
                                    @if($concept->reel)
                                        <template x-if="!conceptReelDeleted">
                                            <div class="sm:col-span-2">
                                                <p class="text-xs font-semibold text-amber-800 mb-2">Reel / Vidéo du concept</p>
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ $concept->reel }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-amber-100 text-amber-800 rounded-lg text-sm font-medium hover:bg-amber-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                                        Voir la vidéo
                                                    </a>
                                                    <button 
                                                        type="button"
                                                        @click="removeConceptReel()"
                                                        class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium transition-colors">
                                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                    <input type="hidden" name="concept_reel" value="{{ $concept->reel }}">
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <!-- Photos Dropzone -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Photos du produit *
                                </label>
                                <div id="photos-dropzone" class="dropzone border-2 border-dashed border-teal-300 rounded-xl p-8 text-center hover:border-pink-400 hover:bg-pink-50/50 transition-all duration-300 bg-gradient-to-br from-white to-pink-50/30">
                                    <div class="dz-message">
                                        <div class="mx-auto w-16 h-16 bg-gradient-to-br from-pink-100 to-rose-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="mt-2 text-sm font-medium text-gray-700">Glissez-déposez vos photos ici ou cliquez pour sélectionner</p>
                                        <p class="mt-1 text-xs text-gray-500">JPG, PNG jusqu'à 5MB (max 10 fichiers)</p>
                                    </div>
                                </div>
                            @error('photos')
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            </div>

                            <!-- 3D Model Dropzone -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Modèle 3D (ZIP) *
                                </label>
                                <div id="model-dropzone" class="dropzone border-2 border-dashed border-indigo-300 rounded-xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/50 transition-all duration-300 bg-gradient-to-br from-white to-indigo-50/30">
                                    <div class="dz-message">
                                        <div class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <p class="mt-2 text-sm font-medium text-gray-700">Glissez-déposez votre modèle 3D (ZIP) ici</p>
                                        <p class="mt-1 text-xs text-gray-500">Fichier ZIP jusqu'à 50MB</p>
                                    </div>
                                </div>
                                @error('folderModel')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Reel Dropzone -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 border border-teal-100 shadow-sm">
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Reel (Vidéo) <span class="text-gray-500 font-normal">(Optionnel)</span>
                                </label>
                                <div id="reel-dropzone" class="dropzone border-2 border-dashed border-cyan-300 rounded-xl p-8 text-center hover:border-cyan-500 hover:bg-cyan-50/50 transition-all duration-300 bg-gradient-to-br from-white to-cyan-50/30">
                                    <div class="dz-message">
                                        <div class="mx-auto w-16 h-16 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="mt-2 text-sm font-medium text-gray-700">Glissez-déposez votre vidéo ici (Optionnel)</p>
                                        <p class="mt-1 text-xs text-gray-500">Fichier vidéo jusqu'à 100MB</p>
                                    </div>
                                </div>
                                @error('reel')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 4: Measurements -->
                        <div x-show="currentStep === 4" class="space-y-6 animate-fadeIn">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">Dimensions et Mesures <span class="text-gray-500 text-lg">(Optionnel)</span></h3>
                            </div>
                            
                            <div class="p-6 border-2 border-amber-200 rounded-xl bg-gradient-to-br from-amber-50/50 to-orange-50/50 backdrop-blur-sm shadow-sm">
                                <div class="mb-4">
                                    @php $measureSize = old('measure_size', optional(optional($concept)->measure)->size ?? ''); @endphp
                                    <label for="measure_size" class="block text-sm font-medium text-gray-700">Taille de mesure</label>
                                    <select name="measure_size" id="measure_size"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                        <option value="">Sélectionner une taille</option>
                                        <option value="SMALL" {{ $measureSize === 'SMALL' ? 'selected' : '' }}>Petit (SMALL)</option>
                                        <option value="MEDIUM" {{ $measureSize === 'MEDIUM' ? 'selected' : '' }}>Moyen (MEDIUM)</option>
                                        <option value="LARGE" {{ $measureSize === 'LARGE' ? 'selected' : '' }}>Grand (LARGE)</option>
                                    </select>
                                    @error('measure_size')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    @php $dim = optional(optional($concept)->measure)->dimension; @endphp
                                    <div>
                                        <label for="length" class="block text-sm font-medium text-gray-700">Longueur</label>
                                        <input type="number" step="0.01" name="length" id="length"
                                            value="{{ old('length', $dim?->length ?? '') }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                        @error('length')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="width" class="block text-sm font-medium text-gray-700">Largeur</label>
                                        <input type="number" step="0.01" name="width" id="width"
                                            value="{{ old('width', $dim?->width ?? '') }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                        @error('width')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="height" class="block text-sm font-medium text-gray-700">Hauteur</label>
                                        <input type="number" step="0.01" name="height" id="height"
                                            value="{{ old('height', $dim?->height ?? '') }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                        @error('height')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unité</label>
                                        <select name="unit" id="unit"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="">Sélectionner une unité</option>
                                            <option value="CM" {{ old('unit', $dim?->unit ?? 'CM') === 'CM' ? 'selected' : '' }}>CM (Centimètres)</option>
                                            <option value="FT" {{ old('unit', $dim?->unit) === 'FT' ? 'selected' : '' }}>FT (Pieds)</option>
                                            <option value="INCH" {{ old('unit', $dim?->unit) === 'INCH' ? 'selected' : '' }}>INCH (Pouces)</option>
                                        </select>
                                        @error('unit')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    @php $weight = optional(optional($concept)->measure)->weight; @endphp
                                    <div>
                                        <label for="weight_value" class="block text-sm font-medium text-gray-700">Valeur du poids</label>
                                        <input type="number" step="0.01" name="weight_value" id="weight_value"
                                            value="{{ old('weight_value', $weight?->weight_value ?? '') }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                        @error('weight_value')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="weight_unit" class="block text-sm font-medium text-gray-700">Unité de poids</label>
                                        <select name="weight_unit" id="weight_unit"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="">Sélectionner une unité</option>
                                            <option value="KG" {{ old('weight_unit', $weight?->weight_unit ?? 'KG') === 'KG' ? 'selected' : '' }}>KG (Kilogrammes)</option>
                                            <option value="LB" {{ old('weight_unit', $weight?->weight_unit) === 'LB' ? 'selected' : '' }}>LB (Livres)</option>
                                        </select>
                                        @error('weight_unit')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Review -->
                        <div x-show="currentStep === 5" 
                             x-init="$watch('currentStep', value => { if (value === 5) updateReview(); })"
                             class="space-y-6 animate-fadeIn">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Vérification</h3>
                            </div>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 mb-6 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-green-800">
                                        Veuillez vérifier toutes les informations avant de soumettre.
                                    </p>
                                </div>
                            </div>
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-green-100 shadow-sm space-y-4">
                                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Nom:
                                    </div>
                                    <span id="review-name" class="text-sm text-gray-600 font-medium">-</span>
                                </div>
                                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Catégorie:
                                    </div>
                                    <span id="review-category" class="text-sm text-gray-600 font-medium">-</span>
                                </div>
                                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Prix:
                                    </div>
                                    <span id="review-price" class="text-sm text-gray-600 font-medium">-</span>
                                </div>
                                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Photos:
                                    </div>
                                    <span id="review-photos" class="text-sm text-gray-600 font-medium">-</span>
                                </div>
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        Modèle 3D:
                                    </div>
                                    <span id="review-model" class="text-sm text-gray-600 font-medium">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between mt-10 pt-6 border-t-2 border-gradient-to-r from-purple-200 to-indigo-200">
                            <button type="button" 
                                    @click="prevStep()" 
                                    x-show="currentStep > 1"
                                    class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-xl hover:from-gray-200 hover:to-gray-300 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Précédent
                            </button>
                            <div class="ml-auto"></div>
                            <button type="button" 
                                    @click="nextStep()" 
                                    x-show="currentStep < totalSteps"
                                    class="flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white rounded-xl hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold">
                                Suivant
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <button type="button" 
                                    @click="submitForm()" 
                                    x-show="currentStep === totalSteps"
                                    class="flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 text-white rounded-xl hover:from-green-600 hover:via-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Créer le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
        
        .dropzone {
            min-height: 180px;
        }
        .dropzone .dz-preview {
            margin: 10px;
        }
        .dropzone .dz-preview .dz-image {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .dropzone.dz-drag-hover {
            border-color: #ec4899 !important;
            background-color: rgba(236, 72, 153, 0.1) !important;
        }
        
        /* Select2 Styling */
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--multiple {
            border: 2px solid #c7d2fe !important;
            border-radius: 0.75rem !important;
            min-height: 48px;
            padding: 4px;
            background: white;
        }
        .select2-container--default .select2-selection--multiple:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%) !important;
            border: none !important;
            color: white;
            padding: 4px 12px;
            margin: 4px;
            border-radius: 0.5rem;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
            margin-right: 6px;
            font-weight: bold;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fbbf24 !important;
        }
        .select2-container--default .select2-search--inline .select2-search__field {
            margin-top: 4px;
            padding: 4px;
        }
        .select2-dropdown {
            border: 2px solid #c7d2fe !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>

</x-admin-layout>
