<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-green-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-110">
                            <x-application-logo class="h-8 w-8 fill-current text-white" />
                        </div>
                        <div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                Craftaroom
                            </span>
                            <span class="block text-xs text-gray-500 font-medium">Client</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1 ml-10">
                    <a href="{{ route('customer.dashboard') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('customer.dashboard') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-green-100/30' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Dashboard</span>
                        @if(request()->routeIs('customer.dashboard'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-green-500 to-emerald-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('customer.orders.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('customer.orders.*') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-green-100/30' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Mes Commandes</span>
                        @if(request()->routeIs('customer.orders.*'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-green-500 to-emerald-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('customer.cart') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('cart.*') || request()->routeIs('customer.cart') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-green-100/30' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Panier</span>
                        @if(request()->routeIs('cart.*') || request()->routeIs('customer.cart'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-green-500 to-emerald-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('customer.favorites.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('customer.favorites.*') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-green-100/30' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span>Favoris</span>
                        @if(request()->routeIs('customer.favorites.*'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-green-500 to-emerald-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <div class="relative" 
                     x-data="{ 
                         open: false,
                         init() {
                             this.$nextTick(() => {
                                 this.open = false;
                             });
                         },
                         closeDropdown() { 
                             this.open = false; 
                         },
                         toggleDropdown() { 
                             this.open = !this.open; 
                         }
                     }" 
                     @click.outside="closeDropdown()"
                     @keydown.escape.window="closeDropdown()">
                    <button @click.prevent="toggleDropdown()" 
                            type="button"
                            class="group flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-gray-700 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50 transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-300 to-emerald-300 flex items-center justify-center overflow-hidden ring-2 ring-green-300 group-hover:ring-green-400 transition-all">
                            @if(Auth::user()->photoUrl)
                                <img src="{{ Auth::user()->photoUrl }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-lg font-bold text-green-700">{{ substr(Auth::user()->name ?? 'C', 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="hidden lg:block text-left">
                            <div class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">Client</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-green-200 overflow-hidden z-50">
                        <!-- User Info Header -->
                        <div class="p-4 bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 border-b border-green-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-300 to-emerald-300 flex items-center justify-center overflow-hidden ring-2 ring-green-400">
                                    @if(Auth::user()->photoUrl)
                                        <img src="{{ Auth::user()->photoUrl }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl font-bold text-green-700">{{ substr(Auth::user()->name ?? 'C', 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-semibold rounded-full">
                                        Client
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('customer.dashboard') }}" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50 transition-all duration-300">
                                <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Dashboard</span>
                            </a>

                            <a href="{{ route('customer.orders.index') }}" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50 transition-all duration-300">
                                <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Mes Commandes</span>
                            </a>

                            <a href="{{ route('customer.cart') }}" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50 transition-all duration-300">
                                <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Panier</span>
                            </a>

                            <a href="{{ route('customer.favorites.index') }}" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50 transition-all duration-300">
                                <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Favoris</span>
                            </a>

                            <a href="{{ route('customer.profile') }}" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 transition-all duration-300">
                                <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-500 transition-colors">
                                    <svg class="w-5 h-5 text-cyan-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Mon profil</span>
                            </a>

                            <a href="/" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50 transition-all duration-300">
                                <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Retour au site</span>
                            </a>

                            <div class="border-t border-gray-200 my-2"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" @click="closeDropdown()" class="group w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-300">
                                    <div class="p-2 bg-red-100 rounded-lg group-hover:bg-red-500 transition-colors">
                                        <svg class="w-5 h-5 text-red-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <span class="font-semibold">Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-100/30 transition-all duration-300">
                    <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden border-t border-green-200 bg-white/98 backdrop-blur-md">
        <div class="px-4 pt-4 pb-6 space-y-2">
            <!-- User Info Mobile -->
            <div class="px-4 py-3 bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 rounded-xl mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-300 to-emerald-300 flex items-center justify-center overflow-hidden ring-2 ring-green-400">
                        @if(Auth::user()->photoUrl)
                            <img src="{{ Auth::user()->photoUrl }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xl font-bold text-green-700">{{ substr(Auth::user()->name ?? 'C', 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-semibold rounded-full">
                            Client
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('customer.dashboard') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('customer.dashboard') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50' }}">
                <div class="p-1.5 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('customer.orders.index') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('customer.orders.*') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50' }}">
                <div class="p-1.5 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Mes Commandes</span>
            </a>

            <a href="{{ route('customer.cart') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('cart.*') || request()->routeIs('customer.cart') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50' }}">
                <div class="p-1.5 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span>Panier</span>
            </a>

            <a href="{{ route('customer.favorites.index') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('customer.favorites.*') ? 'text-green-600 bg-green-100/50' : 'text-gray-700 hover:text-green-600 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50' }}">
                <div class="p-1.5 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span>Favoris</span>
            </a>

            <div class="border-t border-gray-200 my-3"></div>

            <a href="{{ route('customer.profile') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-cyan-600 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 transition-all duration-300">
                <div class="p-1.5 bg-cyan-100 rounded-lg group-hover:bg-cyan-500 transition-colors">
                    <svg class="w-5 h-5 text-cyan-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span>Mon profil</span>
            </a>

            <a href="/" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-green-600 hover:bg-gradient-to-r hover:from-green-100/50 hover:to-emerald-100/50 transition-all duration-300">
                <div class="p-1.5 bg-green-100 rounded-lg group-hover:bg-green-500 transition-colors">
                    <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span>Retour au site</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="group w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-300">
                    <div class="p-1.5 bg-red-100 rounded-lg group-hover:bg-red-500 transition-colors">
                        <svg class="w-5 h-5 text-red-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </div>
</nav>
