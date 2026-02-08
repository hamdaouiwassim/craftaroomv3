<x-designer-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Mes Concepts
                </h2>
            </div>
            <a href="{{ route('designer.concepts.create') }}" class="flex items-center gap-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500 text-white px-6 py-3 rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau Concept
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
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

                    <div class="mb-6">
                        <form method="GET" action="{{ route('designer.concepts.index') }}" class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[200px] relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un concept..."
                                       class="block w-full pl-12 pr-4 py-3 border-2 border-purple-200 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900 placeholder-gray-400">
                            </div>
                            <div class="flex-shrink-0">
                                <label for="status" class="sr-only">Statut</label>
                                <select name="status" id="status" class="block w-full py-3 pl-4 pr-10 border-2 border-purple-200 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900">
                                    <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>Tous les statuts</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                Filtrer
                            </button>
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('designer.concepts.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                                    Réinitialiser
                                </a>
                            @endif
                        </form>
                    </div>

                    @if($concepts->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-purple-100 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-purple-50 to-indigo-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">#</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Concept</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Catégorie</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Statut</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($concepts as $concept)
                                            <tr class="hover:bg-purple-50/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                            @if($concept->photos->count() > 0)
                                                                <img src="{{ $concept->photos->first()->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover">
                                                            @else
                                                                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0">
                                                            <div class="text-sm font-bold text-gray-900 truncate">{{ $concept->name }}</div>
                                                            @if($concept->description)
                                                                <div class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($concept->description, 60) }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($concept->category)
                                                        <span class="px-3 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 rounded-full text-xs font-semibold">{{ $concept->category->name }}</span>
                                                    @else
                                                        <span class="text-xs text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($concept->status === 'active')
                                                        <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-xs font-semibold">Actif</span>
                                                    @else
                                                        <span class="px-3 py-1 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-xs font-semibold">Inactif</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $concept->created_at->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="{{ route('designer.concepts.show', $concept) }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Voir">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('designer.concepts.delete', $concept) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce concept ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-6 px-6 pb-4">{{ $concepts->links() }}</div>
                        </div>
                    @else
                        <div class="text-center py-16 bg-white rounded-xl border border-purple-100">
                            <div class="inline-block p-4 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full mb-4">
                                <svg class="w-16 h-16 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun concept</h3>
                            <p class="text-gray-600 mb-6">Vous n'avez pas encore créé de concept.</p>
                            <a href="{{ route('designer.concepts.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500 text-white px-6 py-3 rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Créer un concept
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-designer-layout>
