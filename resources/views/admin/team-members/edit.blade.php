<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                Modifier le membre de l'équipe
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                <div class="p-8">
                    @include('admin.inc.messages')

                    <form action="{{ route('admin.team-members.update', $teamMember) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informations de base
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom complet *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $teamMember->name) }}" required
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">Position *</label>
                                    <input type="text" name="position" id="position" value="{{ old('position', $teamMember->position) }}" required
                                           placeholder="ex: Designer, CEO, Art Director"
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('position')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">Biographie</label>
                                    <textarea name="bio" id="bio" rows="4"
                                              class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">{{ old('bio', $teamMember->bio) }}</textarea>
                                    @error('bio')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">Photo</label>
                                    @if($teamMember->photo_url)
                                        <div class="mb-3">
                                            <img src="{{ $teamMember->photo_url }}" alt="{{ $teamMember->name }}" class="w-24 h-24 rounded-lg object-cover border-2 border-purple-200">
                                            <p class="text-xs text-gray-500 mt-1">Photo actuelle</p>
                                        </div>
                                    @endif
                                    <input type="file" name="photo" id="photo" accept="image/*"
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver la photo actuelle</p>
                                    @error('photo')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Ordre d'affichage</label>
                                    <input type="number" name="order" id="order" value="{{ old('order', $teamMember->order) }}" min="0"
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('order')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Informations de contact
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $teamMember->email) }}"
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $teamMember->phone) }}"
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                Réseaux sociaux
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="facebook" class="block text-sm font-semibold text-gray-700 mb-2">Facebook</label>
                                    <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $teamMember->social_links['facebook'] ?? '') }}" placeholder="https://facebook.com/..."
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('facebook')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="twitter" class="block text-sm font-semibold text-gray-700 mb-2">Twitter</label>
                                    <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $teamMember->social_links['twitter'] ?? '') }}" placeholder="https://twitter.com/..."
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('twitter')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="instagram" class="block text-sm font-semibold text-gray-700 mb-2">Instagram</label>
                                    <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $teamMember->social_links['instagram'] ?? '') }}" placeholder="https://instagram.com/..."
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('instagram')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="linkedin" class="block text-sm font-semibold text-gray-700 mb-2">LinkedIn</label>
                                    <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $teamMember->social_links['linkedin'] ?? '') }}" placeholder="https://linkedin.com/in/..."
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('linkedin')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Statut
                            </h3>

                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }}
                                           class="w-5 h-5 text-purple-600 border-purple-300 rounded focus:ring-purple-500">
                                    <span class="text-sm font-semibold text-gray-700">Membre actif</span>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-purple-100">
                            <a href="{{ route('admin.team-members.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                                Annuler
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

