<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Craftaroom') }} - Client</title>

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
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/30">
        <div class="min-h-screen flex">
            @include('customer.layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col md:ml-64">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200 shadow-sm sticky top-0 z-40">
                        <div class="py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                   {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
