<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes Demandes de Construction
        </h2>
    </x-slot>
    <!-- Page Header -->
    <section class="relative bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600 text-white py-12 lg:py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">Mes Demandes de Construction</h1>
                    <p class="text-green-100 text-lg">Suivez l'état de vos demandes auprès des constructeurs</p>
                </div>
            </div>
        </div>
    </section>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-green-50/30 to-emerald-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-green-100">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($requests->count() > 0)
                        <div class="grid gap-6">
                            @foreach($requests as $request)
                                <div class="bg-white rounded-xl border-2 border-green-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                                    <div class="p-6">
                                        <div class="flex items-start gap-6">
                                            <!-- Concept Image -->
                                            <div class="w-32 h-32 flex-shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-green-100 to-emerald-100">
                                                @if($request->concept && $request->concept->photos->count() > 0)
                                                    <img src="{{ $request->concept->photos->first()->url }}" 
                                                         alt="{{ $request->concept->name }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Request Info -->
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between mb-3">
                                                    <div>
                                                        <h3 class="text-xl font-bold text-gray-900 mb-1">
                                                            {{ $request->concept->name ?? 'Unknown Concept' }}
                                                        </h3>
                                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Demandé {{ $request->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @if($request->status === 'pending')
                                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                En attente
                                                            </span>
                                                        @elseif($request->status === 'accepted')
                                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                                </svg>
                                                                Acceptée
                                                            </span>
                                                        @elseif($request->status === 'declined')
                                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                                </svg>
                                                                Refusée
                                                            </span>
                                                        @else
                                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                                </svg>
                                                                Terminée
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($request->message)
                                                    <div class="mb-3 p-3 bg-green-50/50 rounded-lg border border-green-100">
                                                        <p class="text-sm font-semibold text-green-800 mb-1">Votre message:</p>
                                                        <p class="text-sm text-gray-700">{{ $request->message }}</p>
                                                    </div>
                                                @endif

                                                @if($request->customer_notes)
                                                    <div class="mb-3 p-3 bg-blue-50/50 rounded-lg border border-blue-100">
                                                        <p class="text-sm font-semibold text-blue-800 mb-1">Notes additionnelles:</p>
                                                        <p class="text-sm text-gray-700">{{ $request->customer_notes }}</p>
                                                    </div>
                                                @endif

                                                <div class="flex items-center gap-3">
                                                    @if($request->concept->category)
                                                        <span class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 rounded text-xs font-medium">
                                                            {{ $request->concept->category->name }}
                                                        </span>
                                                    @endif
                                                    
                                                    @if($request->concept->source === 'designer')
                                                        <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                                            Designer Concept
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-medium">
                                                            Library Concept
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-4 flex items-center gap-3">
                                                    <a href="{{ route('concepts.show', $request->concept->id) }}" 
                                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Voir le concept
                                                    </a>

                                                    <a href="{{ route('customer.construction-requests.offers', $request->id) }}" 
                                                       class="inline-flex items-center gap-2 px-4 py-2 {{ $request->offers->count() > 0 ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : 'bg-gray-400' }} text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 shadow-md text-sm relative">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Offres
                                                        @if($request->offers->count() > 0)
                                                            <span class="px-2 py-0.5 bg-white text-blue-600 rounded-full text-xs font-bold">{{ $request->offers->count() }}</span>
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-20 bg-white rounded-xl border border-green-100">
                            <svg class="w-24 h-24 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune demande de construction</h3>
                            <p class="text-gray-600 mb-6">Vous n'avez pas encore soumis de demande de construction.</p>
                            <a href="{{ route('concepts.index') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl">
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
