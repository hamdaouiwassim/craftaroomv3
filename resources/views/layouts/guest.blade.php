<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Craftaroom') }} - {{ $title ?? 'Authentication' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white" x-data="{ mobileMenuOpen: false }">
        <!-- Navigation -->
        <nav class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-sky-blue/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-3 group">
                            <div class="p-2 bg-gradient-to-br from-sky-blue to-blue-accent rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-110">
                                <x-application-logo class="h-8 w-8 fill-current text-white" />
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent">
                                Craftaroom
                            </span>
                </a>
            </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="/" class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-sky-blue hover:bg-sky-blue/10 transition-all duration-300 relative">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Home</span>
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-sky-blue to-blue-accent group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{ route('products.index') }}" class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-blue-accent hover:bg-blue-accent/10 transition-all duration-300 relative">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Products</span>
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-accent to-sky-blue group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{ route('concepts.index') }}" class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-purple-500 hover:bg-purple-500/10 transition-all duration-300 relative">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Concepts</span>
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-pink-500 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="#about" class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-sky-blue hover:bg-sky-blue/10 transition-all duration-300 relative">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>About</span>
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-sky-blue to-blue-accent group-hover:w-full transition-all duration-300"></span>
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="group flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-gray-700 hover:text-sky-blue transition-all duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-blue-accent hover:bg-blue-accent/10 transition-all duration-300 relative">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Login</span>
                            </a>
                            <a href="{{ route('register') }}" class="group flex items-center gap-2 bg-gradient-to-r from-sky-blue to-blue-accent text-white px-6 py-2.5 rounded-xl font-bold hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 ml-2">
                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>Register</span>
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-gray-700 hover:text-sky-blue hover:bg-sky-blue/10 transition-all duration-300">
                            <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" 
                 x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="md:hidden border-t border-sky-blue/20 bg-white/98 backdrop-blur-md">
                <div class="px-4 pt-4 pb-6 space-y-2">
                    <a href="/" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-sky-blue hover:bg-gradient-to-r hover:from-sky-blue/10 hover:to-blue-accent/10 transition-all duration-300">
                        <div class="p-1.5 bg-sky-blue/20 rounded-lg group-hover:bg-sky-blue transition-colors">
                            <svg class="w-5 h-5 text-sky-blue group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span>Home</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-blue-accent hover:bg-gradient-to-r hover:from-blue-accent/10 hover:to-sky-blue/10 transition-all duration-300">
                        <div class="p-1.5 bg-blue-accent/20 rounded-lg group-hover:bg-blue-accent transition-colors">
                            <svg class="w-5 h-5 text-blue-accent group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span>Products</span>
                    </a>
                    <a href="{{ route('concepts.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-purple-500 hover:bg-gradient-to-r hover:from-purple-500/10 hover:to-pink-500/10 transition-all duration-300">
                        <div class="p-1.5 bg-purple-500/20 rounded-lg group-hover:bg-purple-500 transition-colors">
                            <svg class="w-5 h-5 text-purple-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span>Concepts</span>
                    </a>
                    <a href="#about" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-sky-blue hover:bg-gradient-to-r hover:from-sky-blue/10 hover:to-blue-accent/10 transition-all duration-300">
                        <div class="p-1.5 bg-sky-blue/20 rounded-lg group-hover:bg-sky-blue transition-colors">
                            <svg class="w-5 h-5 text-sky-blue group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span>About</span>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-sky-blue transition-all duration-300">
                            <div class="p-1.5 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-700 group-hover:text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:text-blue-accent hover:bg-gradient-to-r hover:from-blue-accent/10 hover:to-sky-blue/10 transition-all duration-300">
                            <div class="p-1.5 bg-blue-accent/20 rounded-lg group-hover:bg-blue-accent transition-colors">
                                <svg class="w-5 h-5 text-blue-accent group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent text-white hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg mt-2">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Register</span>
                        </a>
                    @endauth
            </div>
        </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>
    </body>
</html>
