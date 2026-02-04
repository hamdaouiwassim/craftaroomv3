<x-constructor-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('constructor.construction-requests.index') }}" class="p-2 bg-orange-100 hover:bg-orange-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                    Détails de la Demande
                </h2>
            </div>
            <div>
                @if($request->status === 'pending')
                    <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">En attente</span>
                @elseif($request->status === 'accepted')
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Acceptée</span>
                @elseif($request->status === 'declined')
                    <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full text-sm font-semibold">Refusée</span>
                @else
                    <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Terminée</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Concept Information -->
                    <div class="bg-white rounded-xl border-2 border-orange-100 overflow-hidden shadow-lg">
                        <div class="p-6 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Concept demandé
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flex gap-6">
                                <!-- Concept Image -->
                                <div class="w-48 h-48 flex-shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-orange-100 to-amber-100">
                                    @if($request->concept && $request->concept->photos->count() > 0)
                                        <img src="{{ $request->concept->photos->first()->url }}" 
                                             alt="{{ $request->concept->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Concept Details -->
                                <div class="flex-1">
                                    <h4 class="text-2xl font-bold text-gray-900 mb-3">{{ $request->concept->name }}</h4>
                                    
                                    <div class="space-y-2 mb-4">
                                        @if($request->concept->category)
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-gray-600">Catégorie:</span>
                                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-semibold">
                                                    {{ $request->concept->category->name }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($request->concept->rooms->count() > 0)
                                            <div class="flex items-start gap-2">
                                                <span class="text-sm text-gray-600">Pièces:</span>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($request->concept->rooms as $room)
                                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ $room->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if($request->concept->metals->count() > 0)
                                            <div class="flex items-start gap-2">
                                                <span class="text-sm text-gray-600">Métaux:</span>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($request->concept->metals as $metal)
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $metal->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if($request->concept->description)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-700">{{ $request->concept->description }}</p>
                                        </div>
                                    @endif

                                    <div class="mt-4">
                                        <a href="{{ route('concepts.show', $request->concept->id) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-lg font-semibold hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            Voir le concept complet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Messages -->
                    @if($request->message || $request->customer_notes)
                        <div class="bg-white rounded-xl border-2 border-orange-100 overflow-hidden shadow-lg">
                            <div class="p-6 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    Message du client
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                @if($request->message)
                                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                                        <p class="text-sm font-semibold text-green-800 mb-2">Message:</p>
                                        <p class="text-gray-700">{{ $request->message }}</p>
                                    </div>
                                @endif

                                @if($request->customer_notes)
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                        <p class="text-sm font-semibold text-blue-800 mb-2">Notes additionnelles:</p>
                                        <p class="text-gray-700">{{ $request->customer_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl border-2 border-orange-100 overflow-hidden shadow-lg">
                        <div class="p-6 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informations Client
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-200 to-emerald-200 flex items-center justify-center overflow-hidden">
                                    @if($request->customer->photoUrl)
                                        <img src="{{ $request->customer->photoUrl }}" alt="{{ $request->customer->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-lg font-bold text-green-600">{{ substr($request->customer->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $request->customer->name }}</p>
                                    <p class="text-sm text-gray-500">Client</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @if($request->customer->email)
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a href="mailto:{{ $request->customer->email }}" class="text-orange-600 hover:text-orange-700">{{ $request->customer->email }}</a>
                                    </div>
                                @endif

                                @if($request->customer->phone)
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <a href="tel:{{ $request->customer->phone }}" class="text-orange-600 hover:text-orange-700">{{ $request->customer->phone }}</a>
                                    </div>
                                @endif

                                <div class="pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Demande créée le:</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Offer -->
                    <div class="bg-white rounded-xl border-2 border-orange-100 overflow-hidden shadow-lg">
                        <div class="p-6 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $myOffer ? 'Modifier mon offre' : 'Soumettre une offre' }}
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($myOffer)
                                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-800 font-semibold mb-1">✓ Offre déjà soumise</p>
                                    <p class="text-xs text-green-700">Vous pouvez modifier votre offre ci-dessous</p>
                                </div>
                            @endif

                            <form action="{{ route('constructor.construction-requests.submit-offer', $request->id) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prix (MAD) *</label>
                                        <input 
                                            type="number" 
                                            name="price" 
                                            step="0.01"
                                            min="0"
                                            value="{{ old('price', $myOffer->price ?? '') }}"
                                            required
                                            placeholder="Ex: 15000.00"
                                            class="w-full border-2 border-orange-200 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('price')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Délai (jours) *</label>
                                        <input 
                                            type="number" 
                                            name="construction_time_days" 
                                            min="1"
                                            value="{{ old('construction_time_days', $myOffer->construction_time_days ?? '') }}"
                                            required
                                            placeholder="Ex: 30"
                                            class="w-full border-2 border-orange-200 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('construction_time_days')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Détails de l'offre</label>
                                        <textarea 
                                            name="offer_details" 
                                            rows="4"
                                            placeholder="Décrivez votre offre: matériaux, méthode, garantie..."
                                            class="w-full border-2 border-orange-200 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none">{{ old('offer_details', $myOffer->offer_details ?? '') }}</textarea>
                                        @error('offer_details')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl font-bold hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $myOffer ? 'Mettre à jour l\'offre' : 'Soumettre l\'offre' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- All Offers Count -->
                    @if($request->offers->count() > 0)
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-100 p-4">
                            <p class="text-sm text-blue-800 font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $request->offers->count() }} offre(s) soumise(s) par les constructeurs
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-constructor-layout>
