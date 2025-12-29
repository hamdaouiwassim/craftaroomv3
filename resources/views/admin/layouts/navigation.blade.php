<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-purple-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="p-2 bg-gradient-to-br from-purple-500 via-indigo-500 to-teal-500 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-110">
                            <x-application-logo class="h-8 w-8 fill-current text-white" />
                        </div>
                        <div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 via-indigo-600 to-teal-600 bg-clip-text text-transparent">
                                Craftaroom
                            </span>
                            <span class="block text-xs text-gray-500 font-medium">Admin Panel</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1 ml-10">
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('dashboard') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-purple-50' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Dashboard</span>
                        @if(request()->routeIs('dashboard'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-purple-500 to-indigo-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-indigo-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('admin.categories.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Categories</span>
                        @if(request()->routeIs('admin.categories.*'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('admin.products.*') ? 'text-teal-600 bg-teal-50' : 'text-gray-700 hover:text-teal-600 hover:bg-teal-50' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Products</span>
                        @if(request()->routeIs('admin.products.*'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-teal-500 to-cyan-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-teal-500 to-cyan-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('admin.users.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-purple-50' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Users</span>
                        @if(request()->routeIs('admin.users.*'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-purple-500 to-pink-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-pink-500 group-hover:w-full transition-all duration-300"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.team-members.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold transition-all duration-300 relative {{ request()->routeIs('admin.team-members.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Team</span>
                        @if(request()->routeIs('admin.team-members.*'))
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500"></span>
                        @else
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 group-hover:w-full transition-all duration-300"></span>
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
                             // Ensure dropdown is closed on initialization
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
                            class="group flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center overflow-hidden ring-2 ring-purple-200 group-hover:ring-purple-400 transition-all">
                            @if(Auth::user()->photoUrl)
                                <img src="{{ Auth::user()->photoUrl }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-lg font-bold text-purple-600">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="hidden lg:block text-left">
                            <div class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">Administrator</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-purple-600 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-cloak
                         x-ref="dropdown"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-purple-100 overflow-hidden z-50">
                        <!-- User Info Header -->
                        <div class="p-4 bg-gradient-to-r from-purple-50 via-indigo-50 to-teal-50 border-b border-purple-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center overflow-hidden ring-2 ring-purple-300">
                                    @if(Auth::user()->photoUrl)
                                        <img src="{{ Auth::user()->photoUrl }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl font-bold text-purple-600">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-semibold rounded-full">
                                        Admin
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('dashboard') }}" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition-all duration-300">
                                <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-500 transition-colors">
                                    <svg class="w-5 h-5 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Dashboard</span>
                            </a>

                            <a href="{{ route('profile.edit') }}" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 transition-all duration-300">
                                <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-500 transition-colors">
                                    <svg class="w-5 h-5 text-cyan-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="font-semibold">Mon profil</span>
                            </a>

                            <a href="/" @click="closeDropdown()" class="group flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-teal-50 transition-all duration-300">
                                <div class="p-2 bg-indigo-100 rounded-lg group-hover:bg-indigo-500 transition-colors">
                                    <svg class="w-5 h-5 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <button @click="open = !open" class="p-2 rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-all duration-300">
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
         class="md:hidden border-t border-purple-100 bg-white/98 backdrop-blur-md">
        <div class="px-4 pt-4 pb-6 space-y-2">
            <!-- User Info Mobile -->
            <div class="px-4 py-3 bg-gradient-to-r from-purple-50 via-indigo-50 to-teal-50 rounded-xl mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center overflow-hidden ring-2 ring-purple-300">
                        @if(Auth::user()->photoUrl)
                            <img src="{{ Auth::user()->photoUrl }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xl font-bold text-purple-600">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-semibold rounded-full">
                            Admin
                        </span>
                    </div>
        </div>
            </div>

            <a href="{{ route('dashboard') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50' }}">
                <div class="p-1.5 bg-purple-100 rounded-lg group-hover:bg-purple-500 transition-colors">
                    <svg class="w-5 h-5 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50' }}">
                <div class="p-1.5 bg-indigo-100 rounded-lg group-hover:bg-indigo-500 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'text-teal-600 bg-teal-50' : 'text-gray-700 hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50' }}">
                <div class="p-1.5 bg-teal-100 rounded-lg group-hover:bg-teal-500 transition-colors">
                    <svg class="w-5 h-5 text-teal-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.users.index') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50' }}">
                <div class="p-1.5 bg-purple-100 rounded-lg group-hover:bg-purple-500 transition-colors">
                    <svg class="w-5 h-5 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span>Users</span>
            </a>

            <a href="{{ route('admin.team-members.index') }}" 
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('admin.team-members.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50' }}">
                <div class="p-1.5 bg-indigo-100 rounded-lg group-hover:bg-indigo-500 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span>Team</span>
            </a>

            <div class="border-t border-gray-200 my-3"></div>

            <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-cyan-600 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 transition-all duration-300">
                <div class="p-1.5 bg-cyan-100 rounded-lg group-hover:bg-cyan-500 transition-colors">
                    <svg class="w-5 h-5 text-cyan-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span>Mon profil</span>
            </a>

            <a href="/" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-indigo-600 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-teal-50 transition-all duration-300">
                <div class="p-1.5 bg-indigo-100 rounded-lg group-hover:bg-indigo-500 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
