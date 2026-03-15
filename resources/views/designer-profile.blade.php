<x-main-layout>
    <section class="relative bg-gradient-to-br from-purple-900 via-pink-900 to-rose-900 text-white py-12 lg:py-16 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ url()->previous() }}" class="flex items-center gap-2 text-purple-200 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Back</span>
                </a>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-200 to-pink-200 flex items-center justify-center overflow-hidden flex-shrink-0 border-4 border-white/30 shadow-2xl">
                    @if($designer->photoUrl)
                        <img src="{{ $designer->photoUrl }}" alt="{{ $designer->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-5xl font-bold text-purple-600">{{ substr($designer->name ?? 'D', 0, 1) }}</span>
                    @endif
                </div>

                <div class="flex-1">
                    <h1 class="text-3xl md:text-5xl font-bold mb-2 bg-gradient-to-r from-white via-purple-100 to-pink-100 bg-clip-text text-transparent">
                        {{ $designer->name }}
                    </h1>
                    <p class="text-purple-200 text-lg mb-4">Designer</p>

                    <div class="flex flex-wrap gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-lg">{{ $totalConcepts }}</p>
                                <p class="text-purple-200">Concepts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gradient-to-b from-white via-purple-50/30 to-pink-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Concepts by {{ $designer->name }}</h2>
                        <p class="text-gray-600">Explore all active concepts published by this designer</p>
                    </div>

                    @if($concepts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @foreach($concepts as $concept)
                                <a href="{{ route('concepts.show', $concept->id) }}" class="group bg-white rounded-2xl shadow-lg overflow-hidden border border-purple-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="relative aspect-square bg-gradient-to-br from-purple-100 via-pink-100 to-rose-100 overflow-hidden">
                                        @if($concept->photos->count() > 0)
                                            <img src="{{ $concept->photos->first()->url }}" alt="{{ $concept->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-4 right-4">
                                            <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full text-xs font-semibold shadow-lg">
                                                Designer Concept
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors line-clamp-2">
                                            {{ $concept->name }}
                                        </h3>
                                        @if($concept->category)
                                            <p class="text-sm text-gray-500 mb-3">{{ $concept->category->name }}</p>
                                        @endif
                                        <div class="flex flex-wrap gap-2">
                                            @if($concept->rooms->count() > 0)
                                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">{{ $concept->rooms->count() }} room(s)</span>
                                            @endif
                                            @if($concept->metals->count() > 0)
                                                <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-lg text-xs font-medium">{{ $concept->metals->count() }} material(s)</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $concepts->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-purple-100">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No Concepts Yet</h3>
                            <p class="text-gray-600">This designer hasn't published any active concepts yet.</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 sticky top-24 z-20">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Designer Information</h3>
                        </div>

                        @if($designer->email)
                            <div class="mb-4 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <a href="mailto:{{ $designer->email }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700 break-all">{{ $designer->email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($designer->phone)
                            <div class="mb-4 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                                        <a href="tel:{{ $designer->phone }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700">{{ $designer->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($designer->address)
                            <div class="mb-4 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-1">Address</p>
                                        <div class="text-sm text-gray-700">
                                            @if($designer->address->address_line1)
                                                <div>{{ $designer->address->address_line1 }}</div>
                                            @endif
                                            @if($designer->address->address_line2)
                                                <div>{{ $designer->address->address_line2 }}</div>
                                            @endif
                                            @if($designer->address->city || $designer->address->state || $designer->address->country)
                                                <div>
                                                    {{ $designer->address->city }}{{ $designer->address->city && $designer->address->state ? ', ' : '' }}
                                                    {{ $designer->address->state }}{{ ($designer->address->city || $designer->address->state) && $designer->address->country ? ', ' : '' }}
                                                    {{ $designer->address->country }}
                                                </div>
                                            @endif
                                            @if($designer->address->postal_code)
                                                <div>{{ $designer->address->postal_code }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-purple-100 pt-4 mt-6">
                            <p class="text-xs text-gray-500 mb-2">Member since</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $designer->created_at->format('F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-main-layout>
