<x-main-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <!-- Products Header -->
    <section class="relative bg-gradient-to-br from-purple-900 via-indigo-900 to-teal-900 text-white py-16 lg:py-20 overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 backdrop-blur-sm border border-purple-300/30 rounded-full text-sm font-semibold text-purple-200">
                        🛍️ All Products
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white via-purple-100 to-indigo-100 bg-clip-text text-transparent">
                    Explore Our Collection
                </h1>
                <p class="text-xl text-purple-100 max-w-2xl mx-auto">
                    Discover our complete range of handcrafted products
                </p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-16 bg-gradient-to-b from-white via-purple-50/30 to-indigo-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <div class="lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 sticky top-24">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                Filters
                            </h2>
                            @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'rooms', 'metals', 'style_type']))
                                <a href="{{ route('products.index') }}" class="text-sm text-red-500 hover:text-red-600 font-semibold">
                                    Clear All
                                </a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('products.index') }}" id="filter-form" class="space-y-6">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Style
                                </label>
                                <div class="p-1 rounded-2xl bg-gradient-to-r from-purple-100 via-indigo-100 to-amber-100 border border-purple-200 shadow-sm">
                                    <div class="grid grid-cols-2 gap-1">
                                        <a href="{{ request()->fullUrlWithQuery(['style_type' => null, 'page' => null]) }}"
                                           class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request('style_type') === 'artisant' ? 'text-gray-600 hover:text-gray-900' : 'bg-white text-purple-700 shadow-sm' }}">
                                            All
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['style_type' => 'artisant', 'page' => null]) }}"
                                           class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request('style_type') === 'artisant' ? 'bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white shadow-lg' : 'text-gray-600 hover:text-gray-900' }}">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Artisant
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Search -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            @if(request('style_type'))
                                <input type="hidden" name="style_type" value="{{ request('style_type') }}">
                            @endif

                            <!-- Category Filter -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Category
                                </label>
                                <select name="category" onchange="document.getElementById('filter-form').submit();" class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        @if($category->sub_categories->count() > 0)
                                            <optgroup label="{{ $category->name }}">
                                                @foreach($category->sub_categories as $subCategory)
                                                    <option value="{{ $subCategory->id }}" {{ request('category') == $subCategory->id ? 'selected' : '' }}>
                                                        {{ $subCategory->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range Filter -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Price Range
                                </label>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <input type="number" 
                                               name="min_price" 
                                               value="{{ request('min_price') }}" 
                                               placeholder="Min"
                                               min="0"
                                               step="0.01"
                                               class="w-full border-2 border-purple-200 rounded-lg p-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                        <span class="text-gray-500">-</span>
                                        <input type="number" 
                                               name="max_price" 
                                               value="{{ request('max_price') }}" 
                                               placeholder="Max"
                                               min="0"
                                               step="0.01"
                                               class="w-full border-2 border-purple-200 rounded-lg p-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    </div>
                                    <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white py-2 rounded-lg font-semibold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 text-sm">
                                        Apply Price Filter
                                    </button>
                                </div>
                            </div>

                            <!-- Rooms Filter -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Rooms
                                </label>
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($rooms as $room)
                                        <label class="flex items-center gap-2 p-2 hover:bg-purple-50 rounded-lg cursor-pointer transition-colors">
                                            <input type="checkbox" 
                                                   name="rooms[]" 
                                                   value="{{ $room->id }}"
                                                   {{ in_array($room->id, request('rooms', [])) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filter-form').submit();"
                                                   class="w-4 h-4 text-purple-600 border-purple-300 rounded focus:ring-purple-500">
                                            <span class="text-sm text-gray-700">{{ $room->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Metals Filter -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    Metals
                                </label>
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($metals as $metal)
                                        <label class="flex items-center gap-2 p-2 hover:bg-purple-50 rounded-lg cursor-pointer transition-colors">
                                            <input type="checkbox" 
                                                   name="metals[]" 
                                                   value="{{ $metal->id }}"
                                                   {{ in_array($metal->id, request('metals', [])) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filter-form').submit();"
                                                   class="w-4 h-4 text-purple-600 border-purple-300 rounded focus:ring-purple-500">
                                            <span class="text-sm text-gray-700">{{ $metal->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products Content -->
                <div class="flex-1">
                    <!-- Search Bar -->
                    <div class="mb-10" x-data="realtimeSearch({ rowSelector: '.product-search-item' })">
                        <form method="GET" action="{{ route('products.index') }}" class="max-w-2xl">
                            <!-- Preserve existing filters -->
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('min_price'))
                                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            @endif
                            @if(request('max_price'))
                                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            @endif
                            @if(request('rooms'))
                                @foreach(request('rooms') as $room)
                                    <input type="hidden" name="rooms[]" value="{{ $room }}">
                                @endforeach
                            @endif
                            @if(request('metals'))
                                @foreach(request('metals') as $metal)
                                    <input type="hidden" name="metals[]" value="{{ $metal }}">
                                @endforeach
                            @endif
                            @if(request('style_type'))
                                <input type="hidden" name="style_type" value="{{ request('style_type') }}">
                            @endif

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       x-model="searchQuery"
                                       @input="onSearchInput()"
                                       placeholder="Search products..." 
                                       class="block w-full pl-12 pr-12 py-4 border-2 border-purple-200 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900 placeholder-gray-400">
                                <button x-show="searchQuery" @click="clearSearch()" type="button" class="absolute inset-y-0 right-24 pr-2 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Active Filters -->
                    @if(request()->anyFilled(['category', 'min_price', 'max_price', 'rooms', 'metals', 'style_type']))
                        <div class="mb-6 flex flex-wrap gap-2">
                            <span class="text-sm font-semibold text-gray-700">Active Filters:</span>
                            @if(request('style_type') === 'artisant')
                                <span class="px-3 py-1 bg-gradient-to-r from-amber-100 to-orange-100 text-amber-700 rounded-full text-sm font-medium flex items-center gap-2">
                                    Style: Artisant
                                    <a href="{{ request()->fullUrlWithQuery(['style_type' => null]) }}" class="hover:text-red-600">×</a>
                                </span>
                            @endif
                            @if(request('category'))
                                @php
                                    $selectedCategory = \App\Models\Category::find(request('category'));
                                @endphp
                                @if($selectedCategory)
                                    <span class="px-3 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 rounded-full text-sm font-medium flex items-center gap-2">
                                        Category: {{ $selectedCategory->name }}
                                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="hover:text-red-600">×</a>
                                    </span>
                                @endif
                            @endif
                            @if(request('min_price') || request('max_price'))
                                <span class="px-3 py-1 bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 rounded-full text-sm font-medium flex items-center gap-2">
                                    Price: {{ request('min_price') ? '$' . number_format(request('min_price'), 2) : '$0' }} - {{ request('max_price') ? '$' . number_format(request('max_price'), 2) : '$' . number_format($maxPrice, 2) }}
                                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="hover:text-red-600">×</a>
                                </span>
                            @endif
                            @if(request('rooms'))
                                @foreach(request('rooms') as $roomId)
                                    @php
                                        $room = \App\Models\Room::find($roomId);
                                    @endphp
                                    @if($room)
                                        <span class="px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-medium flex items-center gap-2">
                                            Room: {{ $room->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['rooms' => array_values(array_diff(request('rooms', []), [$roomId]))]) }}" class="hover:text-red-600">×</a>
                                        </span>
                                    @endif
                                @endforeach
                            @endif
                            @if(request('metals'))
                                @foreach(request('metals') as $metalId)
                                    @php
                                        $metal = \App\Models\Metal::find($metalId);
                                    @endphp
                                    @if($metal)
                                        <span class="px-3 py-1 bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 rounded-full text-sm font-medium flex items-center gap-2">
                                            Metal: {{ $metal->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['metals' => array_values(array_diff(request('metals', []), [$metalId]))]) }}" class="hover:text-red-600">×</a>
                                        </span>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <!-- Results Count -->
                    <div class="mb-6">
                        <p class="text-gray-600">
                            Showing <span class="font-bold text-purple-600">{{ $products->total() }}</span> product(s)
                        </p>
                    </div>

            @if($products->count() > 0)
                <!-- Products Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6 mb-10">
                    @foreach($products as $product)
                        <div class="product-search-item group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-100" data-search-name="{{ strtolower($product->name) }}">
                            <!-- Product Image -->
                            <a href="{{ route('products.show', $product->id) }}" class="block relative h-64 bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100 overflow-hidden" title="View product details">
                                @if($product->photos->count() > 0)
                                    <img src="{{ $product->photos->first()->url }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-purple-400">
                                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                @if($product->style_type === 'artisant')
                                    <div class="absolute top-0 right-0 z-10 pointer-events-none">
                                        <div class="relative w-28 h-28 overflow-visible">
                                            <div class="absolute top-4 -right-8 rotate-45 bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white text-[10px] font-extrabold uppercase tracking-[0.18em] shadow-lg px-8 py-1.5 text-center">
                                                Artisant
                                            </div>
                                            <div class="absolute top-[61px] right-[3px] w-0 h-0 border-l-[9px] border-l-rose-700 border-t-[9px] border-t-transparent"></div>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </a>
                            
                            <!-- Product Info -->
                            <div class="p-5 bg-gradient-to-br from-white to-purple-50/30">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                    {{ $product->name }}
                                </h3>
                                @if($product->category)
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <p class="text-sm text-indigo-600 font-medium">{{ $product->category->name }}</p>
                                    </div>
                                @endif
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                                <div class="flex items-center justify-between pt-3 border-t border-purple-100">
                                    <div>
                                        <span class="text-xs text-gray-500">Price</span>
                                        <span class="block text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                            {{ $product->currency }}{{ number_format($product->price, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.show', $product->id) }}" class="group/btn flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-teal-500 text-white px-4 py-2.5 rounded-xl hover:from-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-10">
                        {{ $products->appends(request()->query())->links('vendor.pagination.products') }}
                    </div>
                </div>
            </div>
            @else
                <div class="text-center py-16">
                    <div class="inline-block p-4 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full mb-4">
                        <svg class="w-16 h-16 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Products Found</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('search'))
                            No products match your search criteria. Try different keywords.
                        @else
                            No products available at the moment. Check back soon!
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Clear Search
                        </a>
                    @endif
                </div>
            @endif
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

    <script>
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-2xl text-white font-semibold transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-gradient-to-r from-green-500 to-emerald-500' 
                    : 'bg-gradient-to-r from-red-500 to-pink-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                        }
                    </svg>
                    <span class="font-semibold">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</x-main-layout>

