<x-constructor-layout>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                            Tableau de bord Constructeur
                        </h1>
                        <p class="text-gray-600 mt-1">Bienvenue, {{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-amber-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Projets en cours</p>
                            <p class="text-3xl font-bold text-amber-600">0</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-orange-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Produits sauvegardés</p>
                            <p class="text-3xl font-bold text-orange-600">{{ auth()->user()->favorites->count() }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-100 to-yellow-100 rounded-xl">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-yellow-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Commandes</p>
                            <p class="text-3xl font-bold text-yellow-600">0</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-xl">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-amber-100 p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Actions rapides
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('constructor.products.index') }}" class="flex items-center gap-3 p-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="font-semibold">Parcourir les produits</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-3 p-4 bg-gradient-to-r from-orange-500 to-yellow-500 text-white rounded-xl hover:from-orange-600 hover:to-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-semibold">Mon panier</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-xl hover:from-yellow-600 hover:to-amber-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-semibold">Mon profil</span>
                    </a>
                </div>
            </div>

            <!-- Saved Products -->
            @if(auth()->user()->favorites->count() > 0)
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-amber-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            Produits sauvegardés
                        </h2>
                        <a href="{{ route('constructor.products.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold flex items-center gap-1">
                            Voir tout
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach(auth()->user()->favorites->take(6) as $favorite)
                            @if($favorite->product)
                                <div class="bg-white rounded-xl p-4 border border-amber-100 hover:shadow-lg transition-shadow">
                                    <div class="w-full h-40 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 overflow-hidden mb-3">
                                        @if($favorite->product->photos->count() > 0)
                                            <img src="{{ $favorite->product->photos->first()->url }}" alt="{{ $favorite->product->name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $favorite->product->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $favorite->product->currency }}{{ number_format($favorite->product->price, 2) }}</p>
                                    <a href="{{ route('constructor.products.show', $favorite->product->id) }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm">
                                        Voir le produit →
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-amber-100 p-12 text-center">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun produit sauvegardé</h3>
                    <p class="text-gray-600 mb-6">Commencez à explorer et sauvegarder vos produits préférés</p>
                    <a href="{{ route('constructor.products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Parcourir les produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-constructor-layout>

