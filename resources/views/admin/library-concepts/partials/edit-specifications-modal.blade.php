<!-- Edit Specifications Modal -->
<div x-show="activeModal === 'specifications'" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @keydown.escape.window="closeModal()"
     x-init="$watch('activeModal', value => { if(value === 'specifications') { setTimeout(() => initSpecsSelect2(), 100); } })">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal()"></div>
        
        <div class="relative bg-white rounded-2xl shadow-xl max-w-4xl w-full p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Modifier les spécifications</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitSection('specifications', new FormData($event.target))" class="space-y-6" id="specs-form">
                <!-- Rooms with Select2 -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Pièces *
                    </label>
                    <select name="rooms[]" id="specs-rooms-select" multiple required 
                            class="w-full select2-rooms"
                            data-placeholder="Sélectionnez les pièces">
                        @foreach($rooms ?? [] as $room)
                            <option value="{{ $room->id }}" {{ $concept->rooms->contains($room->id) ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        <svg class="w-4 h-4 inline-block text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Sélectionnez une ou plusieurs pièces
                    </p>
                </div>

                <!-- Metals with Select2 -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <svg class="w-5 h-5 inline-block mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Métaux *
                    </label>
                    <select name="metals[]" id="specs-metals-select" multiple required 
                            class="w-full select2-metals"
                            data-placeholder="Sélectionnez les métaux">
                        @foreach($metals ?? [] as $metal)
                            <option value="{{ $metal->id }}" {{ $concept->metals->contains($metal->id) ? 'selected' : '' }}>
                                {{ $metal->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        <svg class="w-4 h-4 inline-block text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Sélectionnez un ou plusieurs métaux
                    </p>
                </div>

                <!-- Measurements -->
                <div class="border-t-2 border-purple-100 pt-6">
                    <h4 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Mesures
                    </h4>
                    
                    <!-- Size -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4 inline-block mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                            Taille
                        </label>
                        <select name="measure_size" class="w-full border-2 border-indigo-200 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                            <option value="">Non spécifié</option>
                            <option value="SMALL" {{ $concept->measure?->size === 'SMALL' ? 'selected' : '' }}>SMALL (Petit)</option>
                            <option value="MEDIUM" {{ $concept->measure?->size === 'MEDIUM' ? 'selected' : '' }}>MEDIUM (Moyen)</option>
                            <option value="LARGE" {{ $concept->measure?->size === 'LARGE' ? 'selected' : '' }}>LARGE (Grand)</option>
                        </select>
                    </div>

                    <!-- Dimensions -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-5 mb-4">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Dimensions
                        </h5>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Longueur</label>
                                <input type="number" step="0.01" name="length" 
                                       value="{{ $concept->measure?->dimension?->length }}"
                                       placeholder="0.00"
                                       class="w-full border-2 border-blue-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Largeur</label>
                                <input type="number" step="0.01" name="width" 
                                       value="{{ $concept->measure?->dimension?->width }}"
                                       placeholder="0.00"
                                       class="w-full border-2 border-blue-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Hauteur</label>
                                <input type="number" step="0.01" name="height" 
                                       value="{{ $concept->measure?->dimension?->height }}"
                                       placeholder="0.00"
                                       class="w-full border-2 border-blue-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Unité</label>
                                <select name="unit" class="w-full border-2 border-blue-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="CM" {{ $concept->measure?->dimension?->unit === 'CM' ? 'selected' : '' }}>CM</option>
                                    <option value="FT" {{ $concept->measure?->dimension?->unit === 'FT' ? 'selected' : '' }}>FT</option>
                                    <option value="INCH" {{ $concept->measure?->dimension?->unit === 'INCH' ? 'selected' : '' }}>INCH</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Weight -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            Poids
                        </h5>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Valeur</label>
                                <input type="number" step="0.01" name="weight_value" 
                                       value="{{ $concept->measure?->weight?->weight_value }}"
                                       placeholder="0.00"
                                       class="w-full border-2 border-green-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Unité</label>
                                <select name="weight_unit" class="w-full border-2 border-green-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                                    <option value="KG" {{ $concept->measure?->weight?->weight_unit === 'KG' ? 'selected' : '' }}>KG</option>
                                    <option value="LB" {{ $concept->measure?->weight?->weight_unit === 'LB' ? 'selected' : '' }}>LB</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t-2 border-gray-200">
                    <button type="button" @click="closeModal()" 
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg font-bold hover:from-purple-600 hover:to-indigo-600 shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function initSpecsSelect2() {
    // Destroy existing Select2 instances if they exist
    if ($('#specs-rooms-select').hasClass('select2-hidden-accessible')) {
        $('#specs-rooms-select').select2('destroy');
    }
    if ($('#specs-metals-select').hasClass('select2-hidden-accessible')) {
        $('#specs-metals-select').select2('destroy');
    }

    // Initialize Select2 for Rooms
    $('#specs-rooms-select').select2({
        placeholder: 'Sélectionnez les pièces',
        allowClear: false,
        width: '100%',
        dropdownParent: $('#specs-form'),
        theme: 'default',
        templateResult: formatRoomOption,
        templateSelection: formatRoomSelection
    });

    // Initialize Select2 for Metals
    $('#specs-metals-select').select2({
        placeholder: 'Sélectionnez les métaux',
        allowClear: false,
        width: '100%',
        dropdownParent: $('#specs-form'),
        theme: 'default',
        templateResult: formatMetalOption,
        templateSelection: formatMetalSelection
    });
}

function formatRoomOption(room) {
    if (!room.id) return room.text;
    return $('<span><svg class="w-4 h-4 inline-block mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>' + room.text + '</span>');
}

function formatRoomSelection(room) {
    if (!room.id) return room.text;
    return $('<span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>' + room.text + '</span>');
}

function formatMetalOption(metal) {
    if (!metal.id) return metal.text;
    return $('<span><svg class="w-4 h-4 inline-block mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>' + metal.text + '</span>');
}

function formatMetalSelection(metal) {
    if (!metal.id) return metal.text;
    return $('<span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>' + metal.text + '</span>');
}
</script>
