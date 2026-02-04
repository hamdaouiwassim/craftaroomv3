<x-customer-layout>
    <!-- Dashboard Header -->
    <section class="relative bg-gradient-to-br from-main-blue via-dark-blue to-sky-blue text-white py-16 lg:py-20 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-sky-blue/20 to-blue-accent/20 backdrop-blur-sm border border-sky-blue/30 rounded-full text-sm font-semibold text-sky-blue">
                        🏠 Tableau de bord
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white via-sky-blue to-blue-accent bg-clip-text text-transparent">
                    Bienvenue, {{ auth()->user()->name }}
                </h1>
                <p class="text-lg text-sky-blue/80">Gérez vos commandes, favoris et préférences</p>
            </div>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="py-16 bg-gradient-to-b from-white via-sky-blue/5 to-blue-accent/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Favorites Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-sky-blue/10 to-blue-accent/10 rounded-xl">
                            <svg class="w-8 h-8 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-sky-blue hover:text-blue-accent transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Mes Favoris</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent">
                        {{ $stats['favorites_count'] }}
                    </p>
                    <p class="mt-4 text-xs text-gray-500">Produits sauvegardés</p>
                </div>

                <!-- Reviews Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-blue-accent/10 to-sky-blue/10 rounded-xl">
                            <svg class="w-8 h-8 text-blue-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Avis donnés</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-accent to-sky-blue bg-clip-text text-transparent">
                        {{ $stats['reviews_count'] }}
                    </p>
                    <p class="mt-4 text-xs text-gray-500">Évaluations partagées</p>
                </div>

                <!-- Cart Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-sky-blue/10 to-blue-accent/10 rounded-xl">
                            <svg class="w-8 h-8 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <a href="{{ route('customer.cart') }}" class="text-sky-blue hover:text-blue-accent transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Mon Panier</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent" id="cart-count">
                        {{ $stats['cart_count'] }}
                    </p>
                    <p class="mt-4 text-xs text-gray-500">Articles en attente</p>
                </div>

                <!-- Orders Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-blue-accent/10 to-sky-blue/10 rounded-xl">
                            <svg class="w-8 h-8 text-blue-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <a href="{{ route('customer.orders.index') }}" class="text-blue-accent hover:text-sky-blue transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Mes Commandes</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-accent to-sky-blue bg-clip-text text-transparent">
                        0
                    </p>
                    <p class="mt-4 text-xs text-gray-500">Commandes passées</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Actions rapides
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('products.index') }}" class="group flex items-center gap-3 p-4 bg-gradient-to-r from-sky-blue to-blue-accent text-white rounded-xl hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="font-semibold">Parcourir les produits</span>
                    </a>
                    <a href="{{ route('customer.cart') }}" class="group flex items-center gap-3 p-4 bg-gradient-to-r from-blue-accent to-sky-blue text-white rounded-xl hover:from-blue-accent/90 hover:to-sky-blue/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-semibold">Mon panier</span>
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="group flex items-center gap-3 p-4 bg-gradient-to-r from-sky-blue to-blue-accent text-white rounded-xl hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="font-semibold">Mes commandes</span>
                    </a>
                    <a href="{{ route('customer.profile') }}" class="group flex items-center gap-3 p-4 bg-gradient-to-r from-blue-accent to-sky-blue text-white rounded-xl hover:from-blue-accent/90 hover:to-sky-blue/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-semibold">Mon profil</span>
                    </a>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Favorites Section -->
                @if($stats['recent_favorites']->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 overflow-hidden">
                        <div class="p-6 border-b border-sky-blue/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Mes favoris récents
                                </h3>
                                <a href="{{ route('products.index') }}" class="text-sm text-sky-blue hover:text-blue-accent font-semibold flex items-center gap-1">
                                    Voir tout
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($stats['recent_favorites'] as $favorite)
                                    @if($favorite->product)
                                        <a href="{{ route('products.show', $favorite->product->id) }}" class="group bg-gradient-to-br from-sky-blue/5 to-blue-accent/5 rounded-xl p-4 border border-sky-blue/20 hover:border-sky-blue/40 hover:shadow-lg transition-all">
                                            <div class="w-full h-32 rounded-lg bg-gradient-to-br from-sky-blue/10 to-blue-accent/10 overflow-hidden mb-3">
                                                @if($favorite->product->photos->count() > 0)
                                                    <img src="{{ $favorite->product->photos->first()->url }}" alt="{{ $favorite->product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-sky-blue">
                                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-gray-900 mb-1 truncate group-hover:text-sky-blue transition-colors">{{ $favorite->product->name }}</h4>
                                            <p class="text-sm font-semibold text-sky-blue">{{ $favorite->product->currency }}{{ number_format($favorite->product->price, 2) }}</p>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 p-6">
                        <div class="text-center py-8">
                            <div class="inline-block p-4 bg-gradient-to-br from-sky-blue/10 to-blue-accent/10 rounded-full mb-4">
                                <svg class="w-12 h-12 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Aucun favori</h3>
                            <p class="text-gray-600 mb-4">Commencez à ajouter des produits à vos favoris</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-blue to-blue-accent text-white px-6 py-3 rounded-xl font-semibold hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all">
                                Parcourir les produits
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Recent Reviews Section -->
                @if($stats['recent_reviews']->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl border border-sky-blue/20 overflow-hidden">
                        <div class="p-6 border-b border-sky-blue/20">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Mes avis récents
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($stats['recent_reviews'] as $review)
                                    <div class="p-4 bg-gradient-to-br from-sky-blue/5 to-blue-accent/5 rounded-xl border border-sky-blue/20">
                                        <div class="flex items-start gap-4">
                                            @if($review->product && $review->product->photos->count() > 0)
                                                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-sky-blue/10 to-blue-accent/10 overflow-hidden flex-shrink-0">
                                                    <img src="{{ $review->product->photos->first()->url }}" alt="{{ $review->product->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1">
                                                    @if($review->product)
                                                        <a href="{{ route('products.show', $review->product->id) }}" class="hover:text-sky-blue transition-colors">
                                                            {{ $review->product->name }}
                                                        </a>
                                                    @else
                                                        Produit supprimé
                                                    @endif
                                                </h4>
                                                @if($review->rating)
                                                    <div class="flex items-center gap-1 mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                @endif
                                                @if($review->comment)
                                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $review->comment }}</p>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script>
        // Load cart count
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) {
                    cartCountEl.textContent = data.count || 0;
                }
            })
            .catch(error => {
                console.error('Error loading cart count:', error);
            });
    </script>
</x-customer-layout>
