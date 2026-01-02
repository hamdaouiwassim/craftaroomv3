<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-sky-blue/10 via-blue-accent/10 to-sky-blue/10">
        <div class="max-w-xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="p-4 bg-gradient-to-br from-sky-blue to-blue-accent rounded-2xl shadow-2xl transform hover:scale-110 transition-all duration-300">
                        <x-application-logo class="h-16 w-16 fill-current text-white" />
                    </div>
                </div>
                <h2 class="text-4xl font-bold bg-gradient-to-r from-sky-blue to-blue-accent bg-clip-text text-transparent mb-2">
                    Bienvenue
                </h2>
                <p class="text-gray-600 text-lg">Connectez-vous à votre compte</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm text-red-600">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Login Form -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl border border-sky-blue/20 p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                            <svg class="w-5 h-5 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            Email
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="block w-full border-2 border-sky-blue/30 rounded-xl shadow-sm p-3 focus:ring-2 focus:ring-sky-blue focus:border-sky-blue transition-all bg-white placeholder-gray-400">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                            <svg class="w-5 h-5 text-blue-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Mot de passe
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="block w-full border-2 border-sky-blue/30 rounded-xl shadow-sm p-3 focus:ring-2 focus:ring-sky-blue focus:border-sky-blue transition-all bg-white placeholder-gray-400">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember"
                                   class="w-5 h-5 text-sky-blue border-2 border-sky-blue/40 rounded focus:ring-2 focus:ring-sky-blue focus:ring-offset-0 cursor-pointer">
                            <span class="ms-2 text-sm font-semibold text-gray-700 group-hover:text-sky-blue transition-colors">
                                Se souvenir de moi
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-sm font-semibold text-sky-blue hover:text-blue-accent hover:underline transition-all">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-sky-blue to-blue-accent text-white rounded-xl font-bold text-lg hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Se connecter
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Ou continuer avec</span>
                    </div>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('auth.google') }}" 
                   class="w-full py-3.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold text-base hover:border-sky-blue hover:bg-sky-blue/5 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Continuer avec Google</span>
                </a>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Pas encore de compte ?
                        <a href="{{ route('register') }}" class="font-bold text-sky-blue hover:text-blue-accent hover:underline transition-all">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
                <div class="absolute top-20 left-10 w-72 h-72 bg-sky-blue/20 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
                <div class="absolute top-40 right-10 w-72 h-72 bg-blue-accent/20 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
                <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-sky-blue/20 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
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
    </style>
</x-guest-layout>
