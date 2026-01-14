@php
    $user = auth()->user();
    $isAdmin = $user->is_admin();
    $isDesigner = $user->role === 1;
    $isConstructor = $user->role === 3;
    $isCustomer = $user->role === 2;
    
    // Determine layout based on role
    if ($isAdmin) {
        $layout = 'admin-layout';
    } elseif ($isDesigner) {
        $layout = 'designer-layout';
    } elseif ($isConstructor) {
        $layout = 'constructor-layout';
    } else {
        $layout = 'client-layout';
    }
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            @if($isAdmin)
                <div class="p-2 bg-gradient-to-br from-sky-blue to-blue-accent rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent">
                    Mes Favoris
                </h2>
            @elseif($isDesigner)
                <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Mes Favoris
                </h2>
            @elseif($isConstructor)
                <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                    Mes Favoris
                </h2>
            @else
                <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Mes Favoris
                </h2>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($isAdmin)
                <div class="bg-gradient-to-br from-white via-sky-blue/30 to-blue-accent/30 overflow-hidden shadow-xl sm:rounded-2xl border border-sky-blue/20">
            @elseif($isDesigner)
                <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-200">
            @elseif($isConstructor)
                <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-orange-200">
            @else
                <div class="bg-gradient-to-br from-white via-green-50/30 to-emerald-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-green-200">
            @endif
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($favorites->count() > 0)
                        <!-- Favorites Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($favorites as $favorite)
                                @php
                                    $product = $favorite->product;
                                @endphp
                                @if($isAdmin)
                                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-sky-blue/20">
                                        <div class="relative h-64 bg-gradient-to-br from-sky-blue/10 via-blue-accent/10 to-sky-blue/10 overflow-hidden">
                                @elseif($isDesigner)
                                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-100">
                                        <div class="relative h-64 bg-gradient-to-br from-purple-100 via-indigo-100 to-purple-100 overflow-hidden">
                                @elseif($isConstructor)
                                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-orange-100">
                                        <div class="relative h-64 bg-gradient-to-br from-orange-100 via-amber-100 to-orange-100 overflow-hidden">
                                @else
                                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-green-100">
                                        <div class="relative h-64 bg-gradient-to-br from-green-100 via-emerald-100 to-green-100 overflow-hidden">
                                @endif
                                        @if($product && $product->photos && $product->photos->count() > 0)
                                            <img src="{{ $product->photos->first()->url }}" 
                                                 alt="{{ $product->name ?? 'Product' }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center {{ $isAdmin ? 'text-sky-blue/40' : ($isDesigner ? 'text-purple-400' : ($isConstructor ? 'text-orange-400' : 'text-green-400')) }}">
                                                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        @if($product && $product->status === 'active')
                                            <span class="absolute top-3 right-3 bg-gradient-to-r from-green-400 to-emerald-500 text-white text-xs px-3 py-1.5 rounded-full font-semibold shadow-lg">
                                                ✓ Active
                                            </span>
                                        @endif
                                        <!-- Remove from favorites button -->
                                        <div class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="removeFavorite({{ $product->id ?? 0 }})" 
                                                    class="p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-red-500 hover:text-white transition-all duration-300">
                                                <svg class="w-5 h-5 text-red-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>
                                    
                                    @if($product)
                                        <!-- Product Info -->
                                        @if($isAdmin)
                                            <div class="p-5 bg-gradient-to-br from-white to-sky-blue/30">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-sky-blue transition-colors">
                                        @elseif($isDesigner)
                                            <div class="p-5 bg-gradient-to-br from-white to-purple-50/30">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                        @elseif($isConstructor)
                                            <div class="p-5 bg-gradient-to-br from-white to-orange-50/30">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-orange-600 transition-colors">
                                        @else
                                            <div class="p-5 bg-gradient-to-br from-white to-green-50/30">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors">
                                        @endif
                                            {{ $product->name }}
                                        </h3>
                                        @if($product->category)
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 {{ $isAdmin ? 'text-blue-accent' : ($isDesigner ? 'text-indigo-500' : ($isConstructor ? 'text-amber-500' : 'text-emerald-500')) }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                <p class="text-sm {{ $isAdmin ? 'text-blue-accent' : ($isDesigner ? 'text-indigo-600' : ($isConstructor ? 'text-amber-600' : 'text-emerald-600')) }} font-medium">{{ $product->category->name }}</p>
                                            </div>
                                        @endif
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                            {{ Str::limit($product->description ?? '', 80) }}
                                        </p>
                                        @if($isAdmin)
                                            <div class="flex items-center justify-between pt-3 border-t border-sky-blue/20">
                                                <div>
                                                    <span class="text-xs text-gray-500">Prix</span>
                                                    <span class="block text-2xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent">
                                                @elseif($isDesigner)
                                            <div class="flex items-center justify-between pt-3 border-t border-purple-100">
                                                <div>
                                                    <span class="text-xs text-gray-500">Prix</span>
                                                    <span class="block text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                                @elseif($isConstructor)
                                            <div class="flex items-center justify-between pt-3 border-t border-orange-100">
                                                <div>
                                                    <span class="text-xs text-gray-500">Prix</span>
                                                    <span class="block text-2xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                                                @else
                                            <div class="flex items-center justify-between pt-3 border-t border-green-100">
                                                <div>
                                                    <span class="text-xs text-gray-500">Prix</span>
                                                    <span class="block text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                                @endif
                                                    {{ $product->currency ?? '€' }}{{ number_format($product->price ?? 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="flex gap-2">
                                                @if($isAdmin)
                                                    <a href="{{ route('products.show', $product->id) }}" class="group/btn flex items-center gap-2 bg-gradient-to-r from-blue-accent to-sky-blue text-white px-4 py-2.5 rounded-xl hover:from-blue-accent hover:to-sky-blue transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                                @elseif($isDesigner)
                                                    <a href="{{ route('products.show', $product->id) }}" class="group/btn flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2.5 rounded-xl hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                                @elseif($isConstructor)
                                                    <a href="{{ route('products.show', $product->id) }}" class="group/btn flex items-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2.5 rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                                @else
                                                    <a href="{{ route('products.show', $product->id) }}" class="group/btn flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-green-500 text-white px-4 py-2.5 rounded-xl hover:from-emerald-600 hover:to-green-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                                @endif
                                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-5">
                                            <p class="text-gray-500 text-sm">Produit supprimé</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center mt-10">
                            {{ $favorites->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            @if($isAdmin)
                                <div class="inline-block p-4 bg-gradient-to-br from-sky-blue/100 to-blue-accent/100 rounded-full mb-4">
                                    <svg class="w-16 h-16 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @elseif($isDesigner)
                                <div class="inline-block p-4 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full mb-4">
                                    <svg class="w-16 h-16 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @elseif($isConstructor)
                                <div class="inline-block p-4 bg-gradient-to-br from-orange-100 to-amber-100 rounded-full mb-4">
                                    <svg class="w-16 h-16 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @else
                                <div class="inline-block p-4 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full mb-4">
                                    <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @endif
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun favori</h3>
                        <p class="text-gray-600 mb-6">Vous n'avez pas encore ajouté de produits à vos favoris.</p>
                        @if($isAdmin)
                            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-blue to-blue-accent text-white px-6 py-3 rounded-xl font-semibold hover:from-sky-blue hover:to-blue-accent transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        @elseif($isDesigner)
                            <a href="{{ route('designer.products.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        @elseif($isConstructor)
                            <a href="{{ route('constructor.products.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        @else
                            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        @endif
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Parcourir les produits
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        async function removeFavorite(productId) {
            if (!confirm('Voulez-vous retirer ce produit de vos favoris ?')) {
                return;
            }

            try {
                const response = await fetch(`/products/${productId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Reload the page to update the favorites list
                    window.location.reload();
                } else {
                    alert('Une erreur est survenue lors de la suppression du favori.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la suppression du favori.');
            }
        }
    </script>
    @endpush
</x-dynamic-component>
