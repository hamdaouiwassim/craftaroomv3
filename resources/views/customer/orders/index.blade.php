<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes Commandes
        </h2>
    </x-slot>
    <!-- Orders Header -->
    <section class="relative bg-gradient-to-br from-main-blue via-dark-blue to-sky-blue text-white py-16 lg:py-20 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-sky-blue/20 to-blue-accent/20 backdrop-blur-sm border border-sky-blue/30 rounded-full text-sm font-semibold text-sky-blue">
                        📦 Mes Commandes
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white via-sky-blue to-blue-accent bg-clip-text text-transparent">
                    Mes Commandes
                </h1>
                <p class="text-lg text-sky-blue/80">Consultez l'historique de vos commandes</p>
            </div>
        </div>
    </section>

    <!-- Orders Content -->
    <section class="py-16 bg-gradient-to-b from-white via-sky-blue/5 to-blue-accent/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(count($orders) > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-sky-blue/20 hover:shadow-xl transition-all duration-300">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="p-3 bg-gradient-to-br from-sky-blue to-blue-accent rounded-xl">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Commande #{{ $order['id'] ?? 'N/A' }}</h3>
                                            <p class="text-sm text-gray-500">Date: {{ $order['date'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Statut</p>
                                            <span class="inline-block px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-sm font-semibold">
                                                {{ $order['status'] ?? 'En attente' }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Articles</p>
                                            <p class="font-semibold text-gray-900">{{ $order['items_count'] ?? 0 }} article(s)</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Total</p>
                                            <p class="text-xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent">
                                                {{ $order['currency'] ?? '$' }}{{ number_format($order['total'] ?? 0, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('customer.orders.show', $order['id'] ?? '#') }}" 
                                       class="px-6 py-3 bg-gradient-to-r from-sky-blue to-blue-accent text-white rounded-xl font-semibold hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        Voir les détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Orders -->
                <div class="text-center py-16">
                    <div class="inline-block p-6 bg-gradient-to-br from-sky-blue/10 to-blue-accent/10 rounded-full mb-6">
                        <svg class="w-24 h-24 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Aucune commande</h3>
                    <p class="text-gray-600 mb-8">Vous n'avez pas encore passé de commande.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-sky-blue to-blue-accent text-white px-8 py-4 rounded-xl font-bold hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Commencer à magasiner
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-customer-layout>

