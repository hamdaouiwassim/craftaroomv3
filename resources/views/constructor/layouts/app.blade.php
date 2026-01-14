<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Craftaroom') }} - Constructeur</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { 
                display: none !important; 
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-orange-50/30 to-amber-50/30">
        <div class="min-h-screen">
            @include('constructor.layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gradient-to-r from-white via-orange-50/30 to-amber-50/30 border-b border-orange-200 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="min-h-screen">
               {{ $slot }}
            </main>
        </div>
    </body>
</html>
