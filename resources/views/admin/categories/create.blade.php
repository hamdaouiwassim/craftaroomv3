<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Créer une nouvelle catégorie
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                <div class="p-8">
                    @include('admin.inc.messages')

                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Informations de base
                            </h3>

                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom de la catégorie *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                           class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                              class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Category Settings -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Paramètres
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Type de catégorie *</label>
                                    <select name="type" id="type" onchange="toggleParentCategory()" required
                                            class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                        <option value="main" {{ old('type') == 'main' ? 'selected' : '' }}>Principale</option>
                                        <option value="sub" {{ old('type') == 'sub' ? 'selected' : '' }}>Secondaire</option>
                                    </select>
                                    @error('type')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="parent-category-group" style="display: {{ old('type') == 'sub' ? 'block' : 'none' }};">
                                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Catégorie parente</label>
                                    <select name="category_id" id="category_id"
                                            class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut *</label>
                                    <select name="status" id="status" required
                                            class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Icon Upload -->
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-purple-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Icône de la catégorie
                            </h3>

                            <div>
                                <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">Télécharger une icône</label>
                                <input type="file" name="icon" id="icon" accept="image/*"
                                       class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-purple-500 file:to-indigo-500 file:text-white hover:file:from-purple-600 hover:file:to-indigo-600">
                                @error('icon')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-purple-100">
                            <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                                Annuler
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-teal-500 text-white rounded-xl font-bold hover:from-indigo-600 hover:via-purple-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                Créer la catégorie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleParentCategory() {
            const categoryTypeSelect = document.getElementById('type');
            const parentCategoryGroup = document.getElementById('parent-category-group');
            
            if (categoryTypeSelect.value === 'main') {
                parentCategoryGroup.style.display = 'none';
                document.getElementById('category_id').value = '';
            } else {
                parentCategoryGroup.style.display = 'block';
            }
        }
    </script>
</x-admin-layout>
