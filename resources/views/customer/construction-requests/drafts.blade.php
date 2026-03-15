<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes Brouillons
        </h2>
    </x-slot>

    <section class="relative bg-gradient-to-br from-blue-600 via-sky-600 to-cyan-600 text-white py-12 lg:py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">Mes Brouillons</h1>
                    <p class="text-blue-100 text-lg">Retrouvez vos demandes enregistrées avant envoi</p>
                </div>
            </div>
        </div>
    </section>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-blue-50/30 to-cyan-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-blue-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-blue-100">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($drafts->count() > 0)
                        <div class="grid gap-6">
                            @foreach($drafts as $draft)
                                @php
                                    $isProductRequest = $draft->request_type === 'product' && $draft->product;
                                    $subject = $isProductRequest ? $draft->product : $draft->concept;
                                    $subjectPhotos = $subject?->photos;
                                    $subjectName = $subject?->name ?? ($isProductRequest ? 'Produit inconnu' : 'Concept inconnu');
                                    $dimensions = $draft->normalized_requested_dimensions;
                                @endphp
                                <div class="bg-white rounded-xl border-2 border-blue-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                                    <div class="p-6">
                                        <div class="flex items-start gap-6">
                                            <div class="w-32 h-32 flex-shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-blue-100 to-cyan-100">
                                                @if($subject && $subjectPhotos && $subjectPhotos->count() > 0)
                                                    <img src="{{ $subjectPhotos->first()->url }}" alt="{{ $subjectName }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1">
                                                <div class="flex items-start justify-between mb-3 gap-4">
                                                    <div>
                                                        <p class="text-xs uppercase tracking-wide font-semibold text-sky-600 mb-1">
                                                            {{ $isProductRequest ? 'Brouillon produit' : 'Brouillon concept' }}
                                                        </p>
                                                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $subjectName }}</h3>
                                                        <p class="text-sm text-gray-600">Mis à jour {{ $draft->updated_at->diffForHumans() }}</p>
                                                    </div>
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                                        </svg>
                                                        Brouillon
                                                    </span>
                                                </div>

                                                @if($dimensions)
                                                    <div class="mb-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                        <p class="text-sm font-semibold text-blue-800 mb-1">Dimensions demandées</p>
                                                        <p class="text-sm text-blue-900">{{ $dimensions['length'] ?? '-' }} × {{ $dimensions['width'] ?? '-' }} × {{ $dimensions['height'] ?? '-' }} {{ $dimensions['unit'] ?? '' }}</p>
                                                    </div>
                                                @endif

                                                @if($draft->message)
                                                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $draft->message }}</p>
                                                @endif

                                                <div class="flex flex-wrap items-center gap-3">
                                                    <a href="{{ route('customer.drafts.edit', $draft->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-500 text-white rounded-lg font-semibold hover:from-sky-600 hover:to-cyan-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Modifier
                                                    </a>

                                                    <form action="{{ route('customer.drafts.send', $draft->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg font-semibold hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                            </svg>
                                                            Envoyer
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('customer.drafts.destroy', $draft->id) }}" method="POST" onsubmit="return confirm('Supprimer ce brouillon ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg font-semibold hover:from-red-600 hover:to-rose-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $drafts->links() }}
                        </div>
                    @else
                        <div class="text-center py-20 bg-white rounded-xl border border-blue-100">
                            <svg class="w-24 h-24 text-blue-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun brouillon</h3>
                            <p class="text-gray-600 mb-6">Enregistrez une demande comme brouillon pour la retrouver ici.</p>
                            <a href="{{ route('concepts.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-500 text-white rounded-xl font-bold hover:from-sky-600 hover:to-cyan-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Parcourir les concepts
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>
