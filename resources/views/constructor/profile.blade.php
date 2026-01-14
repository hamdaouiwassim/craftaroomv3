<x-constructor-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                    Mon Profil
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Update Profile Information -->
            <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-orange-100 mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informations du profil
                    </h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-orange-100 mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Mettre à jour le mot de passe
                    </h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-orange-100">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer le compte
                    </h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-constructor-layout>
