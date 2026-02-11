<x-constructor-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-500 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                Mes Offres
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($offers->count() > 0)
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-orange-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Offres</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $offers->total() }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-orange-100 to-amber-100 rounded-xl">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Acceptées</p>
                                <p class="text-3xl font-bold text-green-600">{{ $offers->where('status', 'accepted')->count() }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-yellow-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">En Attente</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ $offers->where('status', 'pending')->count() }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-xl">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-red-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Rejetées</p>
                                <p class="text-3xl font-bold text-red-600">{{ $offers->where('status', 'rejected')->count() }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-red-100 to-pink-100 rounded-xl">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offers Grid -->
                <div class="grid grid-cols-1 gap-6">
                    @foreach($offers as $offer)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-orange-100 hover:shadow-xl transition-all duration-300">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <!-- Concept Image -->
                                    <div class="w-full md:w-48 flex-shrink-0">
                                        <div class="relative aspect-square rounded-xl overflow-hidden bg-gradient-to-br from-orange-100 via-amber-100 to-yellow-100">
                                            @if($offer->constructionRequest->concept && $offer->constructionRequest->concept->photos->count() > 0)
                                                <img src="{{ $offer->constructionRequest->concept->photos->first()->url }}" 
                                                     alt="{{ $offer->constructionRequest->concept->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            <!-- Status Badge -->
                                            <div class="absolute top-3 right-3">
                                                @if($offer->status === 'accepted')
                                                    <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-xs font-semibold shadow-lg">
                                                        Acceptée
                                                    </span>
                                                @elseif($offer->status === 'rejected')
                                                    <span class="px-3 py-1 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-xs font-semibold shadow-lg">
                                                        Rejetée
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-full text-xs font-semibold shadow-lg">
                                                        En Attente
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Offer Details -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-4">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900 mb-1">
                                                    {{ $offer->constructionRequest->concept->name ?? 'Concept N/A' }}
                                                </h3>
                                                @if($offer->constructionRequest->concept->category)
                                                    <span class="inline-block px-3 py-1 bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 rounded-full text-xs font-semibold">
                                                        {{ $offer->constructionRequest->concept->category->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Offer Info Grid -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                <p class="text-xs text-gray-500 mb-1">Prix Proposé</p>
                                                <p class="text-2xl font-bold text-orange-600">
                                                    {{ $offer->currency }}{{ number_format($offer->price, 2) }}
                                                </p>
                                            </div>

                                            <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                <p class="text-xs text-gray-500 mb-1">Temps de Construction</p>
                                                <p class="text-2xl font-bold text-orange-600">
                                                    {{ $offer->construction_time_days }} jours
                                                </p>
                                            </div>

                                            <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl">
                                                <p class="text-xs text-gray-500 mb-1">Client</p>
                                                <p class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $offer->constructionRequest->customer->name ?? 'N/A' }}
                                                </p>
                                                @if($offer->constructionRequest->customer->email)
                                                    <p class="text-xs text-gray-500 truncate">
                                                        {{ $offer->constructionRequest->customer->email }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Offer Details -->
                                        @if($offer->offer_details)
                                            <div class="mb-4 p-4 bg-gray-50 rounded-xl">
                                                <p class="text-xs text-gray-500 mb-2">Détails de l'Offre</p>
                                                <p class="text-sm text-gray-700">{{ $offer->offer_details }}</p>
                                            </div>
                                        @endif

                                        <!-- Timestamps -->
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Offre soumise le {{ $offer->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                            @if($offer->accepted_at)
                                                <div class="flex items-center gap-1 text-green-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Acceptée le {{ $offer->accepted_at->format('d/m/Y à H:i') }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Action Button -->
                                        <div class="mt-4">
                                            <a href="{{ route('constructor.construction-requests.show', $offer->construction_request_id) }}" 
                                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir la Demande
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $offers->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-orange-100">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 bg-gradient-to-br from-orange-100 to-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune Offre</h3>
                        <p class="text-gray-600 mb-6">Vous n'avez soumis aucune offre pour le moment.</p>
                        <a href="{{ route('constructor.construction-requests.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Voir les Demandes
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-constructor-layout>
