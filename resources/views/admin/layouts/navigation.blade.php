<!-- Sidebar Navigation -->
<div x-data="{ sidebarOpen: false }" class="relative">
    <!-- Mobile Menu Button -->
    <button 
        @click="sidebarOpen = !sidebarOpen"
        class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-500 text-white shadow-lg hover:shadow-xl transition-all duration-300">
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
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-110">
                    <x-application-logo class="h-8 w-8 fill-current text-white" />
                </div>
                <div>
                    <span class="text-xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                        Craftaroom
                    </span>
                    <span class="block text-xs text-gray-500 font-medium">Admin Panel</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.metals.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.metals.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Metals</span>
            </a>

            <a href="{{ route('admin.rooms.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.rooms.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Rooms</span>
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Products</span>
            </a>

           

            <a href="{{ route('admin.library-concepts.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.library-concepts.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                <span>Library Concepts</span>
            </a>

            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Users</span>
            </a>

            <a href="{{ route('admin.team-members.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.team-members.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Team</span>
            </a>

            <a href="{{ route('admin.favorites.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300 {{ request()->routeIs('admin.favorites.*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-lg' : 'text-gray-700 hover:bg-teal-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Favoris</span>
            </a>
        </nav>

        <!-- User Profile Section -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-lg">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center overflow-hidden">
                    @if(auth()->user()->photoUrl)
                        <img src="{{ auth()->user()->photoUrl }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-lg font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">Admin</p>
                </div>
            </div>
            
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
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
