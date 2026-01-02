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
                    Créer un compte
                </h2>
                <p class="text-gray-600 text-lg">Rejoignez notre communauté</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl border border-sky-blue/20 p-8"
                 x-data="{ activeTab: {{ old('role', 2) }} }">
                <!-- User Type Tabs -->
                <div class="mb-6">
                    <div class="flex space-x-2 bg-gray-100 p-1 rounded-xl">
                        <button type="button"
                                @click="activeTab = 2"
                                :class="activeTab === 2 
                                    ? 'bg-gradient-to-r from-sky-blue to-blue-accent text-white shadow-lg' 
                                    : 'text-gray-600 hover:text-sky-blue'"
                                class="flex-1 py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Client
                            </div>
                        </button>
                        <button type="button"
                                @click="activeTab = 1"
                                :class="activeTab === 1 
                                    ? 'bg-gradient-to-r from-sky-blue to-blue-accent text-white shadow-lg' 
                                    : 'text-gray-600 hover:text-sky-blue'"
                                class="flex-1 py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                Designer
                            </div>
                        </button>
                        <button type="button"
                                @click="activeTab = 3"
                                :class="activeTab === 3 
                                    ? 'bg-gradient-to-r from-sky-blue to-blue-accent text-white shadow-lg' 
                                    : 'text-gray-600 hover:text-sky-blue'"
                                class="flex-1 py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Constructeur
                            </div>
                        </button>
                    </div>
                    <!-- Tab Description -->
                    <div class="mt-3 text-center">
                        <p class="text-xs text-gray-500" x-show="activeTab === 2">
                            Créez un compte pour découvrir et acheter nos produits
                        </p>
                        <p class="text-xs text-gray-500" x-show="activeTab === 1">
                            Rejoignez notre communauté de designers et vendez vos créations
                        </p>
                        <p class="text-xs text-gray-500" x-show="activeTab === 3">
                            Accédez à nos outils professionnels pour vos projets de construction
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <!-- Hidden role field that updates based on active tab -->
                    <input type="hidden" name="role" :value="activeTab">

                    <!-- Inline Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Nom complet
                            </label>
                            <input id="name" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="name"
                                   placeholder="Entrez votre nom complet"
                                   class="block w-full border-2 border-sky-blue/30 rounded-lg shadow-sm p-2.5 text-sm focus:ring-2 focus:ring-sky-blue focus:border-sky-blue transition-all bg-white placeholder-gray-400">
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Email
                            </label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="username"
                                   placeholder="exemple@email.com"
                                   class="block w-full border-2 border-sky-blue/30 rounded-lg shadow-sm p-2.5 text-sm focus:ring-2 focus:ring-sky-blue focus:border-sky-blue transition-all bg-white placeholder-gray-400">
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Mot de passe
                            </label>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Minimum 8 caractères"
                                   class="block w-full border-2 border-sky-blue/30 rounded-lg shadow-sm p-2.5 text-sm focus:ring-2 focus:ring-sky-blue focus:border-sky-blue transition-all bg-white placeholder-gray-400">
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Confirmer le mot de passe
                            </label>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Confirmez votre mot de passe"
                                   class="block w-full border-2 border-sky-blue/30 rounded-lg shadow-sm p-2.5 text-sm focus:ring-2 focus:ring-sky-blue focus:border-sky-blue transition-all bg-white placeholder-gray-400">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3 bg-gradient-to-r from-sky-blue to-blue-accent text-white rounded-lg font-bold text-base hover:from-sky-blue/90 hover:to-blue-accent/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Créer mon compte
                        </button>
                    </div>
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

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Déjà un compte ?
                        <a href="{{ route('login') }}" class="font-bold text-sky-blue hover:text-blue-accent hover:underline transition-all">
                            Se connecter
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
