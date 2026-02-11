<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        Tableau de bord
        </h2>
                    <p class="text-sm text-gray-600 mt-1">Bienvenue, {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @include('admin.inc.messages')

            <!-- Welcome Section -->
            <div class="bg-gradient-to-br from-purple-500 via-indigo-500 to-teal-500 rounded-2xl shadow-2xl overflow-hidden border border-purple-200">
                <div class="p-8 text-white">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex-1">
                            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                                Bienvenue sur Craftaroom Admin
                            </h1>
                            <p class="text-lg text-purple-100 mb-6 max-w-2xl">
                                Gérez votre plateforme de produits artisanaux avec facilité. 
                                Surveillez les statistiques, gérez les produits, les catégories et les utilisateurs depuis ce tableau de bord centralisé.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Gérer les produits
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Gérer les catégories
                                </a>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-48 h-48 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg class="w-32 h-32 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Products -->
                <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 rounded-2xl shadow-xl border border-purple-100 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="text-purple-600 hover:text-purple-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Produits</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ $stats['total_products'] }}
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold">
                            {{ $stats['active_products'] }} actifs
                        </span>
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full font-semibold">
                            {{ $stats['inactive_products'] }} inactifs
                        </span>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="bg-gradient-to-br from-white via-indigo-50/30 to-teal-50/30 rounded-2xl shadow-xl border border-indigo-100 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-indigo-100 to-teal-100 rounded-xl">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.categories.index') }}" class="text-indigo-600 hover:text-indigo-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Catégories Principales</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-teal-600 bg-clip-text text-transparent">
                        {{ $stats['total_categories'] }}
                    </p>
                    <p class="mt-4 text-xs text-gray-500">Organisez vos produits par catégories</p>
                </div>

                <!-- Total Users -->
                <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 rounded-2xl shadow-xl border border-teal-100 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-xl">
                            <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-teal-600 hover:text-teal-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Utilisateurs</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                        {{ $stats['total_users'] }}
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">
                            {{ $stats['total_designers'] }} designers
                        </span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold">
                            {{ $stats['total_customers'] }} clients
                        </span>
                    </div>
                </div>

                <!-- Reviews & Favorites -->
                <div class="bg-gradient-to-br from-white via-pink-50/30 to-rose-50/30 rounded-2xl shadow-xl border border-pink-100 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-pink-100 to-rose-100 rounded-xl">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Engagement</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Avis</span>
                            <span class="text-xl font-bold text-pink-600">{{ $stats['total_reviews'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Favoris</span>
                            <span class="text-xl font-bold text-rose-600">{{ $stats['total_favorites'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Products -->
                <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 rounded-2xl shadow-xl border border-purple-100 overflow-hidden">
                    <div class="p-6 border-b border-purple-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Produits récents
                            </h3>
                            <a href="{{ route('admin.products.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-semibold flex items-center gap-1">
                                Voir tout
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($stats['recent_products']->count() > 0)
                            <div class="space-y-4">
                                @foreach($stats['recent_products'] as $product)
                                    <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-purple-100 hover:shadow-md transition-shadow">
                                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 overflow-hidden flex-shrink-0">
                                            @if($product->photos->count() > 0)
                                                <img src="{{ $product->photos->first()->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                @if($product->category)
                                                    <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">{{ $product->category->name }}</span>
                                                @endif
                                                <span class="text-xs px-2 py-1 rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-purple-600 hover:text-purple-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-gray-500">Aucun produit pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-gradient-to-br from-white via-indigo-50/30 to-teal-50/30 rounded-2xl shadow-xl border border-indigo-100 overflow-hidden">
                    <div class="p-6 border-b border-indigo-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Utilisateurs récents
                            </h3>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold flex items-center gap-1">
                                Voir tout
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($stats['recent_users']->count() > 0)
                            <div class="space-y-4">
                                @foreach($stats['recent_users'] as $user)
                                    <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-indigo-100 hover:shadow-md transition-shadow">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-200 to-teal-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($user->photoUrl)
                                                <img src="{{ $user->photoUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-lg font-bold text-indigo-600">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate">{{ $user->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                @php
                                                    $roleNames = [0 => 'Admin', 1 => 'Designer', 2 => 'Customer', 3 => 'Constructor'];
                                                    $roleName = $roleNames[$user->role] ?? 'Unknown';
                                                    $roleColors = [0 => 'from-red-500 to-pink-500', 1 => 'from-blue-500 to-cyan-500', 2 => 'from-green-500 to-emerald-500', 3 => 'from-amber-500 to-orange-500'];
                                                    $roleColor = $roleColors[$user->role] ?? 'from-gray-500 to-gray-600';
                                                @endphp
                                                <span class="text-xs px-2 py-1 bg-gradient-to-r {{ $roleColor }} text-white rounded-full font-semibold">
                                                    {{ $roleName }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-gray-500">Aucun utilisateur pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 rounded-2xl shadow-xl border border-purple-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Actions rapides
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.products.create') }}" class="group flex items-center gap-3 p-4 bg-white rounded-xl border border-purple-100 hover:border-purple-300 hover:shadow-lg transition-all">
                        <div class="p-2 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-lg group-hover:from-purple-200 group-hover:to-indigo-200 transition-colors">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Nouveau produit</h4>
                            <p class="text-xs text-gray-500">Ajouter un produit</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.categories.create') }}" class="group flex items-center gap-3 p-4 bg-white rounded-xl border border-indigo-100 hover:border-indigo-300 hover:shadow-lg transition-all">
                        <div class="p-2 bg-gradient-to-br from-indigo-100 to-teal-100 rounded-lg group-hover:from-indigo-200 group-hover:to-teal-200 transition-colors">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Nouvelle catégorie</h4>
                            <p class="text-xs text-gray-500">Créer une catégorie</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.create') }}" class="group flex items-center gap-3 p-4 bg-white rounded-xl border border-teal-100 hover:border-teal-300 hover:shadow-lg transition-all">
                        <div class="p-2 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-lg group-hover:from-teal-200 group-hover:to-cyan-200 transition-colors">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">Nouvel utilisateur</h4>
                            <p class="text-xs text-gray-500">Ajouter un utilisateur</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.team-members.create') }}" class="group flex items-center gap-3 p-4 bg-white rounded-xl border border-pink-100 hover:border-pink-300 hover:shadow-lg transition-all">
                        <div class="p-2 bg-gradient-to-br from-pink-100 to-rose-100 rounded-lg group-hover:from-pink-200 group-hover:to-rose-200 transition-colors">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">Nouveau membre</h4>
                            <p class="text-xs text-gray-500">Ajouter à l'équipe</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
