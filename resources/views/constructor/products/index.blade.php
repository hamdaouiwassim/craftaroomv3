<x-main-layout>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                                Produits sauvegardés
                            </h1>
                            <p class="text-gray-600 mt-1">Vos produits favoris pour vos projets</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            @if(auth()->user()->favorites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach(auth()->user()->favorites as $favorite)
                        @if($favorite->product)
                            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-amber-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                                <div class="w-full h-64 bg-gradient-to-br from-amber-100 to-orange-100 overflow-hidden">
                                    @if($favorite->product->photos->count() > 0)
                                        <img src="{{ $favorite->product->photos->first()->url }}" alt="{{ $favorite->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-amber-400">
                                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $favorite->product->name }}</h3>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($favorite->product->description, 100) }}</p>
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-2xl font-bold text-amber-600">{{ $favorite->product->currency }}{{ number_format($favorite->product->price, 2) }}</span>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $favorite->product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $favorite->product->status === 'active' ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('products.show', $favorite->product->id) }}" class="block w-full text-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg font-semibold hover:from-amber-600 hover:to-orange-600 transition-all duration-300">
                                        Voir le produit
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-amber-100 p-12 text-center">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun produit sauvegardé</h3>
                    <p class="text-gray-600 mb-6">Commencez à explorer et sauvegarder vos produits préférés</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Parcourir les produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-main-layout>

