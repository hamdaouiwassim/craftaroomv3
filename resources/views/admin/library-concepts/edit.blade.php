<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.library-concepts.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Modifier : {{ $concept->name }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-purple-100">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.library-concepts.update', $concept) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom *</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $concept->name) }}"
                                class="block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1">Catégorie *</label>
                            <select name="category_id" id="category_id" required class="block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                                        @foreach($category->sub_categories ?? [] as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $concept->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </optgroup>
                                    @if(!$category->sub_categories || $category->sub_categories->isEmpty())
                                        <option value="{{ $category->id }}" {{ old('category_id', $concept->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Statut *</label>
                            <select name="status" id="status" required class="block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                <option value="active" {{ old('status', $concept->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ old('status', $concept->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description *</label>
                            <textarea name="description" id="description" rows="4" required class="block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">{{ old('description', $concept->description) }}</textarea>
                            @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="rooms" class="block text-sm font-semibold text-gray-700 mb-1">Pièces *</label>
                            <select name="rooms[]" id="rooms" multiple class="block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                @php $selectedRooms = old('rooms', $concept->rooms->pluck('id')->all()); @endphp
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ in_array($room->id, $selectedRooms) ? 'selected' : '' }}>{{ $room->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Ctrl/Cmd + clic pour sélectionner plusieurs.</p>
                            @error('rooms')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="metals" class="block text-sm font-semibold text-gray-700 mb-1">Métaux *</label>
                            <select name="metals[]" id="metals" multiple class="block w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 bg-white">
                                @php $selectedMetals = old('metals', $concept->metals->pluck('id')->all()); @endphp
                                @foreach($metals as $metal)
                                    <option value="{{ $metal->id }}" {{ in_array($metal->id, $selectedMetals) ? 'selected' : '' }}>{{ $metal->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Ctrl/Cmd + clic pour sélectionner plusieurs.</p>
                            @error('metals')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Mesures (optionnel)</h3>
                            @php
                                $measure = $concept->measure;
                                $dim = $measure?->dimension;
                                $weight = $measure?->weight;
                            @endphp
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="measure_size" class="block text-sm font-medium text-gray-700 mb-1">Taille</label>
                                    <select name="measure_size" id="measure_size" class="block w-full border border-gray-300 rounded-lg p-2">
                                        <option value="">—</option>
                                        <option value="SMALL" {{ old('measure_size', $measure?->size) === 'SMALL' ? 'selected' : '' }}>SMALL</option>
                                        <option value="MEDIUM" {{ old('measure_size', $measure?->size) === 'MEDIUM' ? 'selected' : '' }}>MEDIUM</option>
                                        <option value="LARGE" {{ old('measure_size', $measure?->size) === 'LARGE' ? 'selected' : '' }}>LARGE</option>
                                    </select>
                                </div>
                                <div></div>
                                <div>
                                    <label for="length" class="block text-sm font-medium text-gray-700 mb-1">Longueur</label>
                                    <input type="number" step="0.01" name="length" id="length" value="{{ old('length', $dim?->length) }}" class="block w-full border border-gray-300 rounded-lg p-2">
                                </div>
                                <div>
                                    <label for="width" class="block text-sm font-medium text-gray-700 mb-1">Largeur</label>
                                    <input type="number" step="0.01" name="width" id="width" value="{{ old('width', $dim?->width) }}" class="block w-full border border-gray-300 rounded-lg p-2">
                                </div>
                                <div>
                                    <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Hauteur</label>
                                    <input type="number" step="0.01" name="height" id="height" value="{{ old('height', $dim?->height) }}" class="block w-full border border-gray-300 rounded-lg p-2">
                                </div>
                                <div>
                                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unité</label>
                                    <select name="unit" id="unit" class="block w-full border border-gray-300 rounded-lg p-2">
                                        <option value="">—</option>
                                        <option value="CM" {{ old('unit', $dim?->unit) === 'CM' ? 'selected' : '' }}>CM</option>
                                        <option value="FT" {{ old('unit', $dim?->unit) === 'FT' ? 'selected' : '' }}>FT</option>
                                        <option value="INCH" {{ old('unit', $dim?->unit) === 'INCH' ? 'selected' : '' }}>INCH</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="weight_value" class="block text-sm font-medium text-gray-700 mb-1">Poids</label>
                                    <input type="number" step="0.01" name="weight_value" id="weight_value" value="{{ old('weight_value', $weight?->weight_value) }}" class="block w-full border border-gray-300 rounded-lg p-2">
                                </div>
                                <div>
                                    <label for="weight_unit" class="block text-sm font-medium text-gray-700 mb-1">Unité poids</label>
                                    <select name="weight_unit" id="weight_unit" class="block w-full border border-gray-300 rounded-lg p-2">
                                        <option value="">—</option>
                                        <option value="KG" {{ old('weight_unit', $weight?->weight_unit) === 'KG' ? 'selected' : '' }}>KG</option>
                                        <option value="LB" {{ old('weight_unit', $weight?->weight_unit) === 'LB' ? 'selected' : '' }}>LB</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <a href="{{ route('admin.library-concepts.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300">Annuler</a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl font-bold hover:from-purple-600 hover:to-indigo-600">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
