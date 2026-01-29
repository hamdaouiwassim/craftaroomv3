<x-constructor-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('constructor.products.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                    {{ $source === 'library' ? 'Concepts de la bibliothèque' : 'Concepts designers' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-orange-100">
                <div class="p-6">
                    <p class="text-gray-600 mb-6">
                        {{ $source === 'library' ? 'Choisissez un concept de la bibliothèque (ajouté par l’administration) pour créer un produit. Le formulaire sera pré-rempli avec les informations du concept (modifiables).' : 'Choisissez un concept créé par un designer pour créer un produit. Le formulaire sera pré-rempli avec les informations du concept (modifiables).' }}
                    </p>

                    @if($concepts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($concepts as $concept)
                                <div class="bg-white rounded-xl border-2 border-orange-100 overflow-hidden shadow-sm hover:border-orange-300 hover:shadow-md transition-all">
                                    <div class="aspect-video bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center overflow-hidden">
                                        @if($concept->photos->count() > 0)
                                            <img src="{{ $concept->photos->first()->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-900 truncate">{{ $concept->name }}</h3>
                                        @if($concept->category)
                                            <p class="text-sm text-gray-500 mt-1">{{ $concept->category->name }}</p>
                                        @endif
                                        @if($concept->description)
                                            <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ Str::limit($concept->description, 80) }}</p>
                                        @endif
                                        <a href="{{ route('constructor.products.create', ['concept_id' => $concept->id]) }}" class="mt-4 inline-flex items-center gap-2 w-full justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-lg font-semibold hover:from-orange-600 hover:to-amber-600 transition-all text-sm">
                                            Utiliser ce concept
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $concepts->links() }}</div>
                    @else
                        <div class="text-center py-12 bg-white rounded-xl border border-orange-100">
                            <p class="text-gray-600 mb-4">
                                {{ $source === 'library' ? 'Aucun concept dans la bibliothèque pour le moment.' : 'Aucun concept designer disponible.' }}
                            </p>
                            <a href="{{ route('constructor.products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                                Retour aux produits
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-constructor-layout>
