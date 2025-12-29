<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Détails du membre de l'équipe
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.team-members.edit', $teamMember) }}" class="flex items-center gap-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-3 rounded-xl font-bold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('admin.team-members.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Photo Section -->
                        <div class="md:col-span-1">
                            <div class="bg-white rounded-xl p-6 border border-purple-100 shadow-sm">
                                @if($teamMember->photo_url)
                                    <img src="{{ $teamMember->photo_url }}" alt="{{ $teamMember->name }}" class="w-full rounded-xl object-cover mb-4">
                                @else
                                    <div class="w-full h-64 bg-gradient-to-br from-purple-200 to-indigo-200 rounded-xl flex items-center justify-center mb-4">
                                        <span class="text-6xl font-bold text-purple-600">{{ substr($teamMember->name ?? 'T', 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="text-center">
                                    @if($teamMember->is_active)
                                        <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full text-sm font-semibold">
                                            Actif
                                        </span>
                                    @else
                                        <span class="px-4 py-2 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-full text-sm font-semibold">
                                            Inactif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Information Section -->
                        <div class="md:col-span-2 space-y-6">
                            <!-- Basic Info -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Informations de base
                                </h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-500">Nom complet</span>
                                        <p class="text-lg font-bold text-gray-900">{{ $teamMember->name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-semibold text-gray-500">Position</span>
                                        <p class="text-lg text-gray-900">{{ $teamMember->position }}</p>
                                    </div>
                                    @if($teamMember->bio)
                                        <div>
                                            <span class="text-sm font-semibold text-gray-500">Biographie</span>
                                            <p class="text-gray-700 leading-relaxed">{{ $teamMember->bio }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-sm font-semibold text-gray-500">Ordre d'affichage</span>
                                        <p class="text-gray-900">{{ $teamMember->order }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            @if($teamMember->email || $teamMember->phone)
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Informations de contact
                                    </h3>
                                    <div class="space-y-3">
                                        @if($teamMember->email)
                                            <div>
                                                <span class="text-sm font-semibold text-gray-500">Email</span>
                                                <p class="text-gray-900">{{ $teamMember->email }}</p>
                                            </div>
                                        @endif
                                        @if($teamMember->phone)
                                            <div>
                                                <span class="text-sm font-semibold text-gray-500">Téléphone</span>
                                                <p class="text-gray-900">{{ $teamMember->phone }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Social Media -->
                            @if($teamMember->social_links && count(array_filter($teamMember->social_links)))
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Réseaux sociaux
                                    </h3>
                                    <div class="flex flex-wrap gap-3">
                                        @if(isset($teamMember->social_links['facebook']))
                                            <a href="{{ $teamMember->social_links['facebook'] }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                                Facebook
                                            </a>
                                        @endif
                                        @if(isset($teamMember->social_links['twitter']))
                                            <a href="{{ $teamMember->social_links['twitter'] }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                </svg>
                                                Twitter
                                            </a>
                                        @endif
                                        @if(isset($teamMember->social_links['instagram']))
                                            <a href="{{ $teamMember->social_links['instagram'] }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                                Instagram
                                            </a>
                                        @endif
                                        @if(isset($teamMember->social_links['linkedin']))
                                            <a href="{{ $teamMember->social_links['linkedin'] }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                                LinkedIn
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Timestamps -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Informations système
                                </h3>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><span class="font-semibold">Créé le:</span> {{ $teamMember->created_at->format('d/m/Y à H:i') }}</p>
                                    <p><span class="font-semibold">Modifié le:</span> {{ $teamMember->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

