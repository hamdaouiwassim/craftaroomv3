<!-- Edit Basic Information Modal -->
<div x-show="activeModal === 'basic'" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @keydown.escape.window="closeModal()">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal()"></div>
        
        <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Modifier les informations</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitSection('basic', new FormData($event.target))" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom *</label>
                    <input type="text" name="name" value="{{ $concept->name }}" required
                           class="w-full border-2 border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                    <select name="category_id" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @foreach($categories ?? [] as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->sub_categories ?? [] as $sub)
                                    <option value="{{ $sub->id }}" {{ $concept->category_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                @endforeach
                            </optgroup>
                            @if(!$category->sub_categories || $category->sub_categories->isEmpty())
                                <option value="{{ $category->id }}" {{ $concept->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Statut *</label>
                    <select name="status" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="active" {{ $concept->status === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ $concept->status === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                    <textarea name="description" rows="5" required
                              class="w-full border-2 border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ $concept->description }}</textarea>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="closeModal()" 
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg font-bold hover:from-purple-600 hover:to-indigo-600">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
