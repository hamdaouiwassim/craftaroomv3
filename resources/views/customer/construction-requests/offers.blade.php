<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Offres de Construction
        </h2>
    </x-slot>
    <!-- Page Header -->
    <section class="relative bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600 text-white py-12 lg:py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('customer.construction-requests.index') }}" class="p-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">Offres des Constructeurs</h1>
                    <p class="text-green-100 text-lg">Pour: {{ $request->concept->name }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($request->acceptedOffer())
                <div class="mb-6 p-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl shadow-lg">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-2xl font-bold">Offre Acceptée!</h3>
                    </div>
                    <p class="text-green-100">Vous avez accepté l'offre de <span class="font-bold">{{ $request->acceptedOffer()->constructor->name }}</span>. Le constructeur va vous contacter pour finaliser les détails.</p>
                </div>
            @endif

            @if($request->offers->count() > 0)
                <div class="grid gap-6">
                    @foreach($request->offers as $offer)
                        <div class="bg-white rounded-xl border-2 {{ $offer->status === 'accepted' ? 'border-green-400 shadow-green-200' : ($offer->status === 'rejected' ? 'border-gray-300 opacity-60' : 'border-green-100') }} overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Constructor Avatar -->
                                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-orange-200 to-amber-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($offer->constructor->photoUrl)
                                                <img src="{{ $offer->constructor->photoUrl }}" alt="{{ $offer->constructor->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-2xl font-bold text-orange-600">{{ substr($offer->constructor->name, 0, 1) }}</span>
                                            @endif
                                        </div>

                                        <!-- Constructor Info -->
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">{{ $offer->constructor->name }}</h3>
                                            <p class="text-sm text-gray-600">Constructeur</p>
                                            @if($offer->constructor->phone)
                                                <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    <a href="tel:{{ $offer->constructor->phone }}" class="text-green-600 hover:text-green-700">{{ $offer->constructor->phone }}</a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div>
                                        @if($offer->status === 'accepted')
                                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Acceptée
                                            </span>
                                        @elseif($offer->status === 'rejected')
                                            <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-semibold">Rejetée</span>
                                        @else
                                            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">En attente</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Offer Details -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-100">
                                        <p class="text-sm text-gray-600 mb-1">Prix</p>
                                        <p class="text-2xl font-bold text-green-600">{{ number_format($offer->price, 2) }} MAD</p>
                                    </div>

                                    <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                        <p class="text-sm text-gray-600 mb-1">Délai</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ $offer->construction_time_days }} jours</p>
                                    </div>

                                    <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg border border-amber-100">
                                        <p class="text-sm text-gray-600 mb-1">Date de l'offre</p>
                                        <p class="text-lg font-bold text-amber-600">{{ $offer->created_at->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $offer->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                @if($offer->offer_details)
                                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 mb-4">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Détails de l'offre:</p>
                                        <p class="text-gray-700 text-sm">{{ $offer->offer_details }}</p>
                                    </div>
                                @endif

                                @if($offer->status === 'pending' && !$request->acceptedOffer())
                                    <form action="{{ route('customer.construction-requests.accept-offer', [$request->id, $offer->id]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir accepter cette offre? Les autres offres seront automatiquement rejetées.')">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Accepter cette offre
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-xl border border-green-100 shadow-lg">
                    <svg class="w-24 h-24 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune offre pour le moment</h3>
                    <p class="text-gray-600">Les constructeurs n'ont pas encore soumis d'offres pour cette demande.</p>
                    <p class="text-sm text-gray-500 mt-2">Vous recevrez une notification dès qu'une offre sera disponible.</p>
                </div>
            @endif
        </div>
    </div>
</x-customer-layout>
