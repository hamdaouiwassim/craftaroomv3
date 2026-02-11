<x-main-layout>
    <!-- Producer Profile Header -->
    <section class="relative bg-gradient-to-br from-purple-900 via-indigo-900 to-teal-900 text-white py-12 lg:py-16 overflow-hidden">
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
                <!-- Producer Avatar -->
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center overflow-hidden flex-shrink-0 border-4 border-white/30 shadow-2xl">
                    @if($producer->photoUrl)
                        <img src="{{ $producer->photoUrl }}" alt="{{ $producer->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-5xl font-bold text-purple-600">{{ substr($producer->name ?? 'P', 0, 1) }}</span>
                    @endif
                </div>

                <!-- Producer Info -->
                <div class="flex-1">
                    <h1 class="text-3xl md:text-5xl font-bold mb-2 bg-gradient-to-r from-white via-purple-100 to-indigo-100 bg-clip-text text-transparent">
                        {{ $producer->name }}
                    </h1>
                    <p class="text-purple-200 text-lg mb-4">
                        @if($producer->role === 'constructor')
                            Artisan Constructor
                        @elseif($producer->role === 'designer')
                            Creative Designer
                        @else
                            Producer
                        @endif
                    </p>

                    <!-- Stats -->
                    <div class="flex flex-wrap gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-lg">{{ $totalProducts }}</p>
                                <p class="text-purple-200">Products</p>
                            </div>
                        </div>
                        @if($totalReviews > 0)
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-white/10 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-lg">{{ number_format($averageRating, 1) }}</p>
                                    <p class="text-purple-200">Rating ({{ $totalReviews }} reviews)</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Producer Details & Products -->
    <section class="py-16 bg-gradient-to-b from-white via-purple-50/30 to-indigo-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Products -->
                <div class="lg:col-span-2">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Products by {{ $producer->name }}</h2>
                        <p class="text-gray-600">Explore all handcrafted products from this artisan</p>
                    </div>

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @foreach($products as $product)
                                <a href="{{ route('products.show', $product->id) }}" class="group bg-white rounded-2xl shadow-lg overflow-hidden border border-purple-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="relative aspect-square bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100 overflow-hidden">
                                        @if($product->photos->count() > 0)
                                            <img src="{{ $product->photos->first()->url }}" 
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        @if($product->status === 'active')
                                            <div class="absolute top-4 right-4">
                                                <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-xs font-semibold shadow-lg">
                                                    Available
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors line-clamp-2">
                                            {{ $product->name }}
                                        </h3>
                                        @if($product->category)
                                            <p class="text-sm text-gray-500 mb-3">{{ $product->category->name }}</p>
                                        @endif
                                        <div class="flex items-center justify-between">
                                            <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                                {{ $product->currency }}{{ number_format($product->price, 2) }}
                                            </span>
                                            @if($product->reviews->count() > 0)
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-600">{{ number_format($product->reviews->avg('rating'), 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-purple-100">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No Products Yet</h3>
                            <p class="text-gray-600">This producer hasn't listed any products yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Contact Info -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 sticky top-24 z-20">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Contact Information</h3>
                        </div>

                        @if($producer->email)
                            <div class="mb-4 p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <a href="mailto:{{ $producer->email }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700 break-all">
                                            {{ $producer->email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($producer->phone)
                            <div class="mb-4 p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                                        <a href="tel:{{ $producer->phone }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700">
                                            {{ $producer->phone }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($producer->address)
                            <div class="mb-4 p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-1">Address</p>
                                        <div class="text-sm text-gray-700">
                                            @if($producer->address->address_line1)
                                                <div>{{ $producer->address->address_line1 }}</div>
                                            @endif
                                            @if($producer->address->address_line2)
                                                <div>{{ $producer->address->address_line2 }}</div>
                                            @endif
                                            @if($producer->address->city || $producer->address->state || $producer->address->country)
                                                <div>
                                                    {{ $producer->address->city }}{{ $producer->address->city && $producer->address->state ? ', ' : '' }}
                                                    {{ $producer->address->state }}{{ ($producer->address->city || $producer->address->state) && $producer->address->country ? ', ' : '' }}
                                                    {{ $producer->address->country }}
                                                </div>
                                            @endif
                                            @if($producer->address->postal_code)
                                                <div>{{ $producer->address->postal_code }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-purple-100 pt-4 mt-6">
                            <p class="text-xs text-gray-500 mb-2">Member since</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $producer->created_at->format('F Y') }}</p>
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
