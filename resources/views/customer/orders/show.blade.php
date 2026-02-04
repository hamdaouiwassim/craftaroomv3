<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails de la Commande
        </h2>
    </x-slot>
    <!-- Order Detail Header -->
    <section class="relative bg-gradient-to-br from-main-blue via-dark-blue to-sky-blue text-white py-16 lg:py-20 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-sky-blue/20 to-blue-accent/20 backdrop-blur-sm border border-sky-blue/30 rounded-full text-sm font-semibold text-sky-blue">
                        📦 Détails de la Commande
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white via-sky-blue to-blue-accent bg-clip-text text-transparent">
                    Commande #{{ $order['id'] ?? 'N/A' }}
                </h1>
            </div>
        </div>
    </section>

    <!-- Order Detail Content -->
    <section class="py-16 bg-gradient-to-b from-white via-sky-blue/5 to-blue-accent/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($order)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Items -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-sky-blue/20">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Articles commandés</h2>
                            <div class="space-y-4">
                                @foreach($order['items'] ?? [] as $item)
                                    <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                                        <div class="w-24 h-24 bg-gradient-to-br from-sky-blue/10 to-blue-accent/10 rounded-lg overflow-hidden flex-shrink-0">
                                            @if(isset($item['image']))
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-sky-blue">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900">{{ $item['name'] ?? 'Produit' }}</h3>
                                            <p class="text-sm text-gray-500">Quantité: {{ $item['quantity'] ?? 1 }}</p>
                                            <p class="text-lg font-bold text-sky-blue mt-2">
                                                {{ $order['currency'] ?? '$' }}{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-sky-blue/20 sticky top-24">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Résumé de la commande</h2>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Sous-total</span>
                                    <span class="font-semibold">{{ $order['currency'] ?? '$' }}{{ number_format($order['subtotal'] ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Livraison</span>
                                    <span class="font-semibold">Gratuite</span>
                                </div>
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-bold text-gray-900">Total</span>
                                        <span class="text-2xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent">
                                            {{ $order['currency'] ?? '$' }}{{ number_format($order['total'] ?? 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 pt-6 border-t border-gray-200">
                                <div>
                                    <p class="text-sm text-gray-500 mb-2">Statut</p>
                                    <span class="inline-block px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-sm font-semibold">
                                        {{ $order['status'] ?? 'En attente' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-2">Date de commande</p>
                                    <p class="font-semibold text-gray-900">{{ $order['date'] ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <a href="{{ route('customer.orders.index') }}" class="block w-full mt-6 text-center px-6 py-3 bg-gradient-to-r from-sky-blue to-blue-accent text-white rounded-xl font-semibold hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300">
                                Retour aux commandes
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-gray-600 mb-8">Commande introuvable.</p>
                    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-sky-blue to-blue-accent text-white px-8 py-4 rounded-xl font-bold hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300">
                        Retour aux commandes
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-customer-layout>

