<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Détails de la catégorie: {{ $category->name }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.categories.edit', $category) }}" class="flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-6 py-3 rounded-xl font-bold hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($category->type === 'main')
                <!-- Main Category with Subcategories -->
                <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                    <div class="p-6">
                        @include('admin.inc.messages')

                        <!-- Category Header -->
                        <div class="bg-white rounded-xl p-6 mb-6 border border-purple-100">
                            <div class="flex items-center gap-4 mb-4">
                                @if($category->icon)
                                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-purple-100 to-indigo-100 overflow-hidden">
                                        <img src="{{ $category->icon->url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
                                    @if($category->description)
                                        <p class="text-gray-600">{{ $category->description }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-semibold">
                                        Principale
                                    </span>
                                    <div class="mt-2">
                                        @if($category->status === 'active')
                                            <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-sm font-semibold">
                                                Actif
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-sm font-semibold">
                                                Inactif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subcategories Section -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Sous-catégories ({{ count($category->sub_categories) }})
                            </h2>
                            <a href="{{ route('admin.categories.create', ['parent' => $category->id]) }}" class="flex items-center gap-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-teal-500 text-white px-6 py-3 rounded-xl font-bold hover:from-indigo-600 hover:via-purple-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Ajouter une sous-catégorie
                            </a>
                        </div>

                        @if(count($category->sub_categories) > 0)
                            <div class="bg-white rounded-xl shadow-sm border border-purple-100 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gradient-to-r from-purple-50 to-indigo-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">#</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Nom</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Icône</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Statut</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($category->sub_categories as $subCategory)
                                                <tr class="hover:bg-purple-50/50 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-bold text-gray-900">{{ $subCategory->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($subCategory->icon)
                                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 overflow-hidden">
                                                                <img src="{{ $subCategory->icon->url }}" alt="{{ $subCategory->name }}" class="w-full h-full object-cover">
                                                            </div>
                                                        @else
                                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($subCategory->status === 'active')
                                                            <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-xs font-semibold">Actif</span>
                                                        @else
                                                            <span class="px-3 py-1 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-xs font-semibold">Inactif</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $subCategory->created_at->format('d/m/Y') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end gap-2">
                                                            <a href="{{ route('admin.categories.show', $subCategory) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('admin.categories.edit', $subCategory) }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12 bg-white rounded-xl border border-purple-100">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="text-gray-600">Aucune sous-catégorie pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Sub Category Details -->
                <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                    <div class="p-8">
                        @include('admin.inc.messages')
                        
                        <div class="bg-white rounded-xl p-8 border border-purple-100">
                            <div class="flex items-center gap-6 mb-6">
                                @if($category->icon)
                                    <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-purple-100 to-indigo-100 overflow-hidden">
                                        <img src="{{ $category->icon->url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
                                    <span class="px-4 py-2 bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 rounded-full text-sm font-semibold">
                                        Secondaire
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Statut</p>
                                    <p>
                                        @if($category->status === 'active')
                                            <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-sm font-semibold">Actif</span>
                                        @else
                                            <span class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-sm font-semibold">Inactif</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Créé le</p>
                                    <p class="font-semibold text-gray-900">{{ $category->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Mis à jour le</p>
                                    <p class="font-semibold text-gray-900">{{ $category->updated_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            @if($category->description)
                                <div class="mt-6 pt-6 border-t border-purple-100">
                                    <p class="text-sm text-gray-500 mb-2">Description</p>
                                    <p class="text-gray-700">{{ $category->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
