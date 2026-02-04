<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Craftaroom') }} - Handcrafted Products</title>

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
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-2">
                            <x-application-logo class="h-10 w-10 fill-current text-gray-800" />
                            <span class="text-xl font-bold text-gray-900">Craftaroom</span>
                        </a>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="/" class="text-gray-700 hover:text-gray-900 font-medium">Home</a>
                        <a href="/#products" class="text-gray-700 hover:text-gray-900 font-medium">Products</a>
                        <a href="/concepts" class="text-gray-700 hover:text-gray-900 font-medium">Concepts</a>
                        <a href="/#about" class="text-gray-700 hover:text-gray-900 font-medium">About</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-gray-800 transition">Register</a>
                        @endauth
                    </div>
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-gray-900">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="/" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">Home</a>
                    <a href="#products" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">Products</a>
                    <a href="#about" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">About</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">Register</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-footer />
    </body>
</html>

