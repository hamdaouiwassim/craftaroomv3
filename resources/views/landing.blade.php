<x-main-layout>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-purple-900 via-indigo-900 to-teal-900 text-white py-20 lg:py-32 overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-teal-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-6">
                    <span class="px-4 py-2 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 backdrop-blur-sm border border-purple-300/30 rounded-full text-sm font-semibold text-purple-200">
                        ✨ Handcrafted Excellence
                    </span>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in bg-gradient-to-r from-white via-purple-100 to-indigo-100 bg-clip-text text-transparent">
                    Discover Handcrafted Excellence
                </h1>
                <p class="text-xl md:text-2xl mb-10 text-purple-100 max-w-3xl mx-auto leading-relaxed">
                    Explore our curated collection of unique handcrafted products. 
                    From elegant jewelry to stunning home decor, find pieces that tell your story.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#products" class="group flex items-center gap-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white px-8 py-4 rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-2xl">
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Shop Now
                    </a>
                    <a href="#about" class="group flex items-center gap-2 border-2 border-white/30 backdrop-blur-sm bg-white/10 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/20 hover:border-white/50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Carousel Section -->
    <section id="products" class="py-20 bg-gradient-to-b from-white via-purple-50/30 to-indigo-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-full text-sm font-semibold text-purple-700">
                        🎨 Featured Collection
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-teal-600 bg-clip-text text-transparent">
                    Featured Products
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Browse through our handpicked collection of premium handcrafted items
                </p>
            </div>

            @if($products->count() > 0)
                <!-- Carousel Container -->
                <div class="relative" x-data="{ 
                    currentSlide: 0, 
                    totalSlides: {{ max(1, ceil($products->count() / 3)) }},
                    nextSlide() {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    },
                    prevSlide() {
                        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                    }
                }">
                    <!-- Products Grid -->
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-500 ease-in-out" 
                             :style="`transform: translateX(-${currentSlide * 100}%)`">
                            @php
                                $slidesCount = max(1, ceil($products->count() / 3));
                            @endphp
                            @for($i = 0; $i < $slidesCount; $i++)
                                <div class="min-w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-2">
                                    @foreach($products->slice($i * 3, 3) as $product)
                                        <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-100">
                                            <!-- Product Image -->
                                            <div class="relative h-64 bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100 overflow-hidden">
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
                                                @if($product->status === 'active')
                                                    <span class="absolute top-3 right-3 bg-gradient-to-r from-green-400 to-emerald-500 text-white text-xs px-3 py-1.5 rounded-full font-semibold shadow-lg">
                                                        ✓ Active
                                                    </span>
                                                @endif
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            </div>
                                            
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
                                                        <button onclick="addToCart({{ $product->id }})" class="group/btn flex items-center gap-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white px-4 py-2.5 rounded-xl hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                            Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <button 
                        @click="prevSlide()"
                        class="absolute left-0 top-1/2 -translate-y-1/2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white shadow-xl rounded-full p-4 hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 z-10 transform hover:scale-110"
                        :class="currentSlide === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button 
                        @click="nextSlide()"
                        class="absolute right-0 top-1/2 -translate-y-1/2 bg-gradient-to-r from-indigo-500 to-teal-500 text-white shadow-xl rounded-full p-4 hover:from-indigo-600 hover:to-teal-600 transition-all duration-300 z-10 transform hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Dots Indicator -->
                    <div class="flex justify-center mt-8 space-x-3">
                        <template x-for="i in totalSlides" :key="i">
                            <button 
                                @click="currentSlide = i - 1"
                                class="transition-all duration-300 transform hover:scale-125"
                                :class="currentSlide === i - 1 ? 'w-10 h-3 rounded-full bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 shadow-lg' : 'w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400'">
                            </button>
                        </template>
                    </div>
                    
                    <!-- View All Products Button -->
                    <div class="flex justify-center mt-10">
                        <a href="/products" class="group inline-flex items-center gap-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white px-8 py-4 rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            View All Products
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">No products available at the moment. Check back soon!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gradient-to-br from-indigo-50 via-purple-50 to-teal-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-block mb-4">
                        <span class="px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full text-sm font-semibold text-indigo-700">
                            🎨 About Us
                        </span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-teal-600 bg-clip-text text-transparent">
                        About Craftaroom
                    </h2>
                    <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                        Craftaroom is your destination for discovering unique, handcrafted products that bring 
                        beauty and artistry into your life. We connect talented artisans with customers who 
                        appreciate quality craftsmanship.
                    </p>
                    <p class="text-lg text-gray-700 mb-8 leading-relaxed">
                        Every product in our collection is carefully curated to ensure the highest standards of 
                        quality and design. From elegant jewelry pieces to stunning home decor, we offer a 
                        diverse range of handcrafted items.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-indigo-100 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-800 font-semibold">Handcrafted Quality</span>
                            </div>
                        </div>
                        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-purple-100 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-800 font-semibold">Unique Designs</span>
                            </div>
                        </div>
                        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-teal-100 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-800 font-semibold">Artisan Made</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-indigo-400 via-purple-500 to-teal-500 rounded-2xl p-8 h-96 flex items-center justify-center shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        <div class="text-center text-white">
                            <svg class="w-48 h-48 mx-auto mb-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-xl font-bold">Craftsmanship</p>
                            <p class="text-sm opacity-90">Quality & Excellence</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Teams Section -->
    <section id="teams" class="py-20 bg-gradient-to-b from-white via-indigo-50/30 to-purple-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-full text-sm font-semibold text-purple-700">
                        👥 Our Team
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-teal-600 bg-clip-text text-transparent">
                    Meet Our Talented Team
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    The creative minds behind Craftaroom, bringing you the finest handcrafted products
                </p>
            </div>

            @if(isset($teamMembers) && $teamMembers->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($teamMembers as $member)
                        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-100">
                            <!-- Team Member Image -->
                            <div class="relative h-64 bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100 overflow-hidden">
                                @if($member->photo_url)
                                    <img src="{{ $member->photo_url }}" 
                                         alt="{{ $member->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-purple-400">
                                        <div class="w-32 h-32 bg-gradient-to-br from-purple-400 via-indigo-400 to-teal-400 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                                            {{ strtoupper(substr($member->name ?? 'T', 0, 1)) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <!-- Team Member Info -->
                            <div class="p-6 bg-gradient-to-br from-white to-purple-50/30 text-center">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                    {{ $member->name }}
                                </h3>
                                <p class="text-sm text-indigo-600 font-semibold mb-3">
                                    {{ $member->position }}
                                </p>
                                @if($member->bio)
                                    <p class="text-xs text-gray-600 line-clamp-2 mb-3">
                                        {{ Str::limit($member->bio, 80) }}
                                    </p>
                                @endif
                                @if($member->social_links && count(array_filter($member->social_links)))
                                    <div class="flex items-center justify-center gap-2 mt-3">
                                        @if(isset($member->social_links['facebook']))
                                            <a href="{{ $member->social_links['facebook'] }}" target="_blank" class="text-blue-600 hover:text-blue-700 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if(isset($member->social_links['linkedin']))
                                            <a href="{{ $member->social_links['linkedin'] }}" target="_blank" class="text-blue-700 hover:text-blue-800 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Default Team Members (if no data) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @for($i = 0; $i < 4; $i++)
                        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-100">
                            <div class="relative h-64 bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100 overflow-hidden">
                                <div class="w-full h-full flex items-center justify-center text-purple-400">
                                    <div class="w-32 h-32 bg-gradient-to-br from-purple-400 via-indigo-400 to-teal-400 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                                        T{{ $i + 1 }}
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="p-6 bg-gradient-to-br from-white to-purple-50/30 text-center">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                    Team Member {{ $i + 1 }}
                                </h3>
                                <p class="text-sm text-indigo-600 font-semibold mb-3">
                                    Designer
                                </p>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-20 bg-gradient-to-br from-purple-900 via-indigo-900 to-teal-900 text-white overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-block mb-6">
                <span class="px-4 py-2 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 backdrop-blur-sm border border-purple-300/30 rounded-full text-sm font-semibold text-purple-200">
                    🚀 Get Started Today
                </span>
            </div>
            <h2 class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-white via-purple-100 to-indigo-100 bg-clip-text text-transparent">
                Ready to Explore?
            </h2>
            <p class="text-xl text-purple-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                Join our community and discover amazing handcrafted products from talented artisans around the world.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-3 bg-gradient-to-r from-white to-purple-50 text-purple-900 px-10 py-4 rounded-xl font-bold hover:from-purple-50 hover:to-white transition-all duration-300 shadow-2xl hover:shadow-purple-500/50 transform hover:scale-105">
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    Go to Dashboard
                </a>
            @else
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="group inline-flex items-center gap-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white px-10 py-4 rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-2xl hover:shadow-purple-500/50 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="group inline-flex items-center gap-3 border-2 border-white/30 backdrop-blur-sm bg-white/10 text-white px-10 py-4 rounded-xl font-bold hover:bg-white/20 hover:border-white/50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </a>
                </div>
            @endauth
        </div>
    </section>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        async function addToCart(productId) {
            try {
                const response = await fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ quantity: 1 })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update cart count
                    const cartCountElements = document.querySelectorAll('#cart-count, #cart-count-mobile');
                    cartCountElements.forEach(el => {
                        if (el) el.textContent = data.cartCount;
                    });

                    // Show success message
                    showNotification('Product added to cart!', 'success');
                } else {
                    showNotification(data.message || 'Failed to add product', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' 
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
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</x-main-layout>

