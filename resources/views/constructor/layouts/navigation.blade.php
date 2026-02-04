<!-- Sidebar Navigation -->
<div x-data="{ sidebarOpen: false }" class="relative">
    <!-- Mobile Menu Button -->
    <button 
        @click="sidebarOpen = !sidebarOpen"
        class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 text-white shadow-lg hover:shadow-xl transition-all duration-300">
        <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Overlay for Mobile -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-40 md:hidden"
        style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform duration-300 ease-in-out md:translate-x-0 bg-white border-r border-gray-200 shadow-xl">
        
        <!-- Logo -->
        <div class="p-6 border-b border-gray-200">
            <a href="{{ route('constructor.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-110">
                    <x-application-logo class="h-8 w-8 fill-current text-white" />
                </div>
                <div>
                    <span class="text-xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                        Craftaroom
                    </span>
                    <span class="block text-xs text-gray-500 font-medium">Constructeur</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('constructor.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('constructor.dashboard') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg' : 'text-gray-700 hover:bg-orange-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('constructor.products.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('constructor.products.*') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg' : 'text-gray-700 hover:bg-orange-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Mes Produits</span>
            </a>

            <a href="{{ route('constructor.favorites.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('constructor.favorites.*') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg' : 'text-gray-700 hover:bg-orange-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Favoris</span>
            </a>

            <a href="{{ route('constructor.construction-requests.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('constructor.construction-requests.*') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg' : 'text-gray-700 hover:bg-orange-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Demandes</span>
            </a>
        </nav>

        <!-- User Profile Section -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center overflow-hidden">
                    @if(auth()->user()->photoUrl)
                        <img src="{{ auth()->user()->photoUrl }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-lg font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">Constructeur</p>
                </div>
            </div>
            
            <div class="mt-3 space-y-1">
                <a href="{{ route('constructor.profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profile</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
