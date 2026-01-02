<x-main-layout>
    <!-- Profile Header -->
    <section class="relative bg-gradient-to-br from-main-blue via-dark-blue to-sky-blue text-white py-16 lg:py-20 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-sky-blue/20 to-blue-accent/20 backdrop-blur-sm border border-sky-blue/30 rounded-full text-sm font-semibold text-sky-blue">
                        👤 Mon Profil
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white via-sky-blue to-blue-accent bg-clip-text text-transparent">
                    Mon Profil
                </h1>
                <p class="text-lg text-sky-blue/80">Gérez vos informations personnelles</p>
            </div>
        </div>
    </section>

    <!-- Profile Content -->
    <section class="py-16 bg-gradient-to-b from-white via-sky-blue/5 to-blue-accent/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-sky-blue/20">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 border border-sky-blue/20 mt-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 border border-sky-blue/20 mt-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </section>
</x-main-layout>

