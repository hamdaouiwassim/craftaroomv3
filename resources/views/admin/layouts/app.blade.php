<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
        @stack('head')
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-sky-blue/10 to-blue-accent/10">
        <div class="min-h-screen flex">
            @include('admin.layouts.navigation')

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

        <div id="global-app-loader" class="hidden fixed inset-0 z-[100] bg-slate-950/55 backdrop-blur-sm">
            <div class="flex min-h-screen items-center justify-center p-6">
                <div class="w-full max-w-sm rounded-3xl bg-white shadow-2xl border border-teal-100 p-8 text-center">
                    <div class="mx-auto mb-5 h-16 w-16 rounded-full bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center shadow-lg">
                        <svg class="h-8 w-8 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-90" d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Veuillez patienter</h3>
                    <p id="global-app-loader-message" class="mt-2 text-sm text-slate-600">Traitement en cours...</p>
                </div>
            </div>
        </div>

        <script>
            window.showAppLoader = function (message = 'Traitement en cours...') {
                const overlay = document.getElementById('global-app-loader');
                const messageNode = document.getElementById('global-app-loader-message');

                if (messageNode) {
                    messageNode.textContent = message;
                }

                if (overlay) {
                    overlay.classList.remove('hidden');
                }

                document.body.style.overflow = 'hidden';
            };

            window.hideAppLoader = function () {
                const overlay = document.getElementById('global-app-loader');

                if (overlay) {
                    overlay.classList.add('hidden');
                }

                document.body.style.overflow = '';
            };
        </script>
        @stack('scripts')
    </body>
</html>
