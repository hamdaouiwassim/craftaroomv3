<!-- Edit Photos Modal -->
<div x-show="activeModal === 'photos'" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @keydown.escape.window="closeModal()">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal()"></div>
        
        <div class="relative bg-white rounded-2xl shadow-xl max-w-4xl w-full p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Gérer les photos</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Current Photos -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Photos actuelles</h4>
                @if($concept->photos->count() > 0)
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($concept->photos as $photo)
                            <div class="relative group">
                                <img src="{{ $photo->url }}" alt="{{ $concept->name }}" class="w-full h-32 object-cover rounded-lg">
                                <form action="{{ route('designer.concepts.delete-photo', [$concept, $photo]) }}" method="POST" class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Supprimer cette photo ?')"
                                            class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucune photo</p>
                @endif
            </div>

            <!-- Upload New Photos -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Ajouter des photos</h4>
                <form action="{{ route('designer.concepts.upload-photos', $concept) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="photos[]" multiple accept="image/*" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG jusqu'à 5MB chacune</p>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="closeModal()" 
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                            Fermer
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg font-bold hover:from-purple-600 hover:to-indigo-600">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
