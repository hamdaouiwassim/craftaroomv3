<!-- Edit 3D Model Modal -->
<div x-show="activeModal === 'model'" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @keydown.escape.window="closeModal()">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal()"></div>
        
        <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Gérer le modèle 3D</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Current Model -->
            @if($concept->threedmodels)
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Modèle actuel</h4>
                    <div class="bg-indigo-50 border-2 border-indigo-200 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $concept->threedmodels->name }}</p>
                                <a href="{{ $concept->threedmodels->url }}" download class="text-sm text-indigo-600 hover:underline">Télécharger</a>
                            </div>
                        </div>
                        <form action="{{ route('admin.library-concepts.delete-model', $concept) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Supprimer ce modèle 3D ?')"
                                    class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Upload New Model -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">{{ $concept->threedmodels ? 'Remplacer' : 'Ajouter' }} le modèle 3D</h4>
                <form action="{{ route('admin.library-concepts.upload-model', $concept) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="folderModel" accept=".zip" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500 mt-2">Fichier ZIP jusqu'à 50MB</p>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="closeModal()" 
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg font-bold hover:from-indigo-600 hover:to-purple-600">
                            {{ $concept->threedmodels ? 'Remplacer' : 'Ajouter' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
