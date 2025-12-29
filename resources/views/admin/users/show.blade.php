<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                    Détails de l'utilisateur
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-6 py-3 rounded-xl font-bold hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- User Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <div class="inline-block w-32 h-32 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center overflow-hidden mb-4">
                                    @if($user->photoUrl)
                                        <img src="{{ $user->photoUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-5xl font-bold text-purple-600">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                                    @endif
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name ?? 'N/A' }}</h3>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                @php
                                    $roleColors = [
                                        0 => 'from-red-500 to-pink-500',
                                        1 => 'from-blue-500 to-cyan-500',
                                        2 => 'from-green-500 to-emerald-500',
                                        3 => 'from-amber-500 to-orange-500',
                                    ];
                                    $roleNames = [
                                        0 => 'Admin',
                                        1 => 'Designer',
                                        2 => 'Customer',
                                        3 => 'Constructor',
                                    ];
                                    $color = $roleColors[$user->role] ?? 'from-gray-500 to-gray-600';
                                    $name = $roleNames[$user->role] ?? 'Unknown';
                                @endphp
                                <span class="inline-block mt-3 px-4 py-2 bg-gradient-to-r {{ $color }} text-white rounded-full text-sm font-semibold">
                                    {{ $name }}
                                </span>
                            </div>

                            <div class="space-y-4 border-t border-purple-100 pt-6">
                                @if($user->phone)
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-purple-100 rounded-lg">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Téléphone</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $user->phone }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($user->country)
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-indigo-100 rounded-lg">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Pays</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $user->country }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($user->currency)
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-teal-100 rounded-lg">
                                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Devise</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $user->currency->name }} ({{ $user->currency->symbol }})</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-cyan-100 rounded-lg">
                                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Membre depuis</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($user->address)
                                <div class="mt-6 pt-6 border-t border-purple-100">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Adresse
                                    </h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        @if($user->address->address_line1)
                                            <p>{{ $user->address->address_line1 }}</p>
                                        @endif
                                        @if($user->address->address_line2)
                                            <p>{{ $user->address->address_line2 }}</p>
                                        @endif
                                        <p>
                                            {{ $user->address->city }}{{ $user->address->city && $user->address->state ? ', ' : '' }}
                                            {{ $user->address->state }}{{ ($user->address->city || $user->address->state) && $user->address->country ? ', ' : '' }}
                                            {{ $user->address->country }}
                                        </p>
                                        @if($user->address->postal_code)
                                            <p>{{ $user->address->postal_code }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- User Products -->
                    <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Produits de l'utilisateur ({{ $user->products->count() }})
                            </h3>

                            @if($user->products->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($user->products as $product)
                                        <div class="bg-white rounded-xl p-4 border border-purple-100 hover:shadow-lg transition-shadow">
                                            <div class="flex gap-4">
                                                <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 overflow-hidden flex-shrink-0">
                                                    @if($product->photos->count() > 0)
                                                        <img src="{{ $product->photos->first()->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-purple-400">
                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h4>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        {{ $product->currency }}{{ number_format($product->price, 2) }}
                                                    </p>
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                        {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 bg-white rounded-xl border border-purple-100">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-gray-600">Cet utilisateur n'a pas encore de produits</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Reviews -->
                    <div class="bg-gradient-to-br from-white via-indigo-50/30 to-teal-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-indigo-100">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Avis de l'utilisateur ({{ $user->reviews->count() }})
                            </h3>

                            @if($user->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($user->reviews as $review)
                                        <div class="bg-white rounded-xl p-5 border border-indigo-100 hover:shadow-lg transition-shadow">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <h4 class="font-bold text-gray-900">
                                                            @if($review->product)
                                                                <a href="{{ route('admin.products.show', $review->product->id) }}" class="hover:text-indigo-600 transition-colors">
                                                                    {{ $review->product->name }}
                                                                </a>
                                                            @else
                                                                Produit supprimé
                                                            @endif
                                                        </h4>
                                                    </div>
                                                    <div class="flex items-center gap-1 mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endfor
                                                        <span class="text-sm text-gray-600 ml-2">({{ $review->rating }}/5)</span>
                                                    </div>
                                                    @if($review->comment)
                                                        <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                                                    @endif
                                                </div>
                                                @if($review->product && $review->product->photos->count() > 0)
                                                    <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-indigo-100 to-teal-100 overflow-hidden flex-shrink-0 ml-4">
                                                        <img src="{{ $review->product->photos->first()->url }}" alt="{{ $review->product->name }}" class="w-full h-full object-cover">
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-indigo-100">
                                                <span>Le {{ $review->created_at->format('d/m/Y à H:i') }}</span>
                                                @if($review->product)
                                                    <a href="{{ route('admin.products.show', $review->product->id) }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                                        Voir le produit →
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 bg-white rounded-xl border border-indigo-100">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    <p class="text-gray-600">Cet utilisateur n'a pas encore d'avis</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Favorites -->
                    <div class="bg-gradient-to-br from-white via-pink-50/30 to-rose-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-pink-100">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Favoris de l'utilisateur ({{ $user->favorites->count() }})
                            </h3>

                            @if($user->favorites->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($user->favorites as $favorite)
                                        @if($favorite->product)
                                            <div class="bg-white rounded-xl p-4 border border-pink-100 hover:shadow-lg transition-shadow">
                                                <div class="flex gap-4">
                                                    <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-pink-100 to-rose-100 overflow-hidden flex-shrink-0">
                                                        @if($favorite->product->photos->count() > 0)
                                                            <img src="{{ $favorite->product->photos->first()->url }}" alt="{{ $favorite->product->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-pink-400">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-bold text-gray-900 mb-1 truncate">
                                                            <a href="{{ route('admin.products.show', $favorite->product->id) }}" class="hover:text-pink-600 transition-colors">
                                                                {{ $favorite->product->name }}
                                                            </a>
                                                        </h4>
                                                        <p class="text-sm text-gray-600 mb-2">
                                                            {{ $favorite->product->currency }}{{ number_format($favorite->product->price, 2) }}
                                                        </p>
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $favorite->product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                            {{ $favorite->product->status === 'active' ? 'Actif' : 'Inactif' }}
                                                        </span>
                                                        <p class="text-xs text-gray-500 mt-2">Ajouté le {{ $favorite->created_at->format('d/m/Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 bg-white rounded-xl border border-pink-100">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <p class="text-gray-600">Cet utilisateur n'a pas encore de favoris</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

