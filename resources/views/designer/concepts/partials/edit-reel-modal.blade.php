<!-- Edit Reel Modal -->
<div x-show="activeModal === 'reel'" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @keydown.escape.window="closeModal()">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal()"></div>
        
        <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Gérer le Reel</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Current Reel -->
            @if($concept->reel)
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Reel actuel</h4>
                    <div class="rounded-xl overflow-hidden bg-black mb-4">
                        <video controls class="w-full h-auto max-h-64">
                            <source src="{{ $concept->reel }}" type="video/mp4">
                        </video>
                    </div>
                    <form action="{{ route('designer.concepts.delete-reel', $concept) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Supprimer ce reel ?')"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600">
                            Supprimer le reel
                        </button>
                    </form>
                </div>
            @endif

            <!-- Upload New Reel -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">{{ $concept->reel ? 'Remplacer' : 'Ajouter' }} le reel</h4>
                <form action="{{ route('designer.concepts.upload-reel', $concept) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="reel" accept="video/*" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                    <p class="text-xs text-gray-500 mt-2">Fichier vidéo jusqu'à 200MB (MP4, MOV, etc.)</p>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="closeModal()" 
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-500 text-white rounded-lg font-bold hover:from-cyan-600 hover:to-teal-600">
                            {{ $concept->reel ? 'Remplacer' : 'Ajouter' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
