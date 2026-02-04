<x-main-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <!-- Concepts Header -->
    <section class="relative bg-gradient-to-br from-purple-900 via-pink-900 to-rose-900 text-white py-16 lg:py-20 overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-purple-500/20 to-pink-500/20 backdrop-blur-sm border border-purple-300/30 rounded-full text-sm font-semibold text-purple-200">
                        ✨ Concepts Library
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white via-purple-100 to-pink-100 bg-clip-text text-transparent">
                    Explore Creative Concepts
                </h1>
                <p class="text-xl text-purple-100 max-w-2xl mx-auto">
                    Discover inspiring designs from our talented designers and curated library collection
                </p>
            </div>
        </div>
    </section>

    <!-- Concepts Section -->
    <section class="py-16 bg-gradient-to-b from-white via-purple-50/30 to-pink-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <div class="lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 sticky top-24">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                Filters
                            </h2>
                            @if(request()->anyFilled(['search', 'source', 'category', 'rooms', 'metals']))
                                <a href="{{ route('concepts.index') }}" class="text-sm text-red-500 hover:text-red-600 font-semibold">
                                    Clear All
                                </a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('concepts.index') }}" id="filter-form" class="space-y-6">
                            <!-- Search -->
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <!-- Source Filter -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Source
                                </label>
                                <select name="source" onchange="document.getElementById('filter-form').submit();" class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    <option value="">All Sources</option>
                                    <option value="designer" {{ request('source') == 'designer' ? 'selected' : '' }}>Designer Concepts</option>
                                    <option value="library" {{ request('source') == 'library' ? 'selected' : '' }}>Library Collection</option>
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <!-- Concepts Content -->
                <div class="flex-1">
                    <!-- Search Bar -->
                    <div class="mb-10">
                        <form method="GET" action="{{ route('concepts.index') }}" class="max-w-2xl">
                            <!-- Preserve existing filters -->
                            @if(request('source'))
                                <input type="hidden" name="source" value="{{ request('source') }}">
                            @endif
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
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

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search concepts..." 
                                       class="block w-full pl-12 pr-4 py-4 border-2 border-purple-200 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900 placeholder-gray-400">
                                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Results -->
                    <div class="mb-6 flex items-center justify-between">
                        <p class="text-gray-600">
                            Found <span class="font-bold text-purple-600">{{ $concepts->total() }}</span> concepts
                        </p>
                    </div>

                    <!-- Concepts Grid -->
                    @if($concepts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($concepts as $concept)
                                <div class="group bg-white rounded-2xl border-2 border-purple-100 overflow-hidden shadow-md hover:shadow-2xl hover:border-purple-300 transition-all duration-300 transform hover:-translate-y-2">
                                    <a href="{{ route('concepts.show', $concept->id) }}" class="block">
                                        <div class="aspect-square bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center overflow-hidden">
                                            @if($concept->photos->count() > 0)
                                                <img src="{{ $concept->photos->first()->url }}" 
                                                     alt="{{ $concept->name }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="p-5">
                                            <div class="mb-2 flex items-center gap-2">
                                                @if($concept->source === 'designer')
                                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded">Designer</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded">Library</span>
                                                @endif
                                                @if($concept->category)
                                                    <span class="px-2 py-0.5 bg-pink-100 text-pink-700 text-xs font-medium rounded">{{ $concept->category->name }}</span>
                                                @endif
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors line-clamp-1">
                                                {{ $concept->name }}
                                            </h3>
                                            @if($concept->description)
                                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                                    {{ Str::limit($concept->description, 80) }}
                                                </p>
                                            @endif
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                @if($concept->rooms && $concept->rooms->count() > 0)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                        </svg>
                                                        {{ $concept->rooms->count() }} room(s)
                                                    </span>
                                                @endif
                                                @if($concept->metals && $concept->metals->count() > 0)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                        </svg>
                                                        {{ $concept->metals->count() }} metal(s)
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-4 flex items-center justify-center py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg font-semibold text-sm group-hover:from-purple-600 group-hover:to-pink-600 transition-all duration-300">
                                                View Details →
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $concepts->links() }}
                        </div>
                    @else
                        <div class="text-center py-20 bg-white rounded-2xl border-2 border-purple-100">
                            <svg class="w-24 h-24 text-purple-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No concepts found</h3>
                            <p class="text-gray-600 mb-6">Try adjusting your filters or search query</p>
                            <a href="{{ route('concepts.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                Clear All Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-main-layout>
