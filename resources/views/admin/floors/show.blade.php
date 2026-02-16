<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-lg">
                    @if($floor->icon)
                        <img src="{{ asset('storage/' . $floor->icon) }}" alt="{{ $floor->name }}" class="w-6 h-6 object-cover">
                    @else
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    @endif
                </div>
                <div>
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                        {{ $floor->name }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ $floor->floorModels->count() }} model(s)</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.floors.edit', $floor) }}" class="px-4 py-2 rounded-xl bg-teal-500/10 text-teal-700 font-semibold hover:bg-teal-500/20 transition-all">Edit</a>
                <a href="{{ route('admin.floors.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Floor Details -->
            <div class="bg-white/90 backdrop-blur border border-teal-100 shadow-xl sm:rounded-2xl p-6">
                @include('admin.inc.messages')
                <div class="flex items-start gap-6">
                    <div class="flex-1 space-y-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500">Floor Name</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $floor->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500">Total Models</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $floor->floorModels->count() }}</p>
                        </div>
                    </div>
                    @if($floor->icon)
                        <div class="w-40 h-40 bg-gray-50 border border-teal-100 rounded-xl flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/' . $floor->icon) }}" alt="{{ $floor->name }}" class="object-cover w-full h-full">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Floor Models Section -->
            <div class="bg-white/90 backdrop-blur border border-teal-100 shadow-xl sm:rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Floor Models</h3>
                        <p class="text-sm text-gray-500">Upload 3D models for this floor type.</p>
                    </div>
                </div>

                <!-- Upload Form -->
                <form method="POST" action="{{ route('admin.floors.models.store', $floor) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 p-4 bg-teal-50/50 rounded-xl">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Model Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" placeholder="e.g. Oak Wood Floor" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="model_file" value="3D Model File" />
                        <input id="model_file" 
                               name="model_file" 
                               type="file" 
                               accept=".zip,.glb,.gltf"
                               required
                               class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:border-teal-500" />
                        <p class="mt-1 text-xs text-gray-500">ZIP, GLB, GLTF (Max: 50MB)</p>
                        <x-input-error :messages="$errors->get('model_file')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="image" value="Preview Image (Optional)" />
                        <input id="image" 
                               name="image" 
                               type="file" 
                               accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:border-teal-500" />
                        <p class="mt-1 text-xs text-gray-500">JPEG, PNG, GIF (Max: 2MB)</p>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Model
                        </button>
                    </div>
                </form>

                <!-- Models List -->
                @if($floor->floorModels->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-teal-50 to-cyan-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Preview</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Size</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Uploaded</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($floor->floorModels as $model)
                                    <tr class="hover:bg-teal-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($model->image)
                                                <img src="{{ asset('storage/' . $model->image) }}" alt="{{ $model->name }}" class="h-16 w-16 rounded-lg object-cover border border-teal-100">
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <span class="text-sm font-semibold text-gray-900">{{ $model->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $model->size ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $model->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                            <a href="{{ $model->url }}" target="_blank" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-cyan-500/10 text-cyan-700 hover:bg-cyan-500/20 transition-all" title="Download">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                <span class="sr-only">Download</span>
                                            </a>
                                            <form action="{{ route('admin.floors.models.destroy', [$floor, $model]) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this model?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-red-500/10 text-red-600 hover:bg-red-500/20 transition-all" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span class="sr-only">Delete</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-10 bg-gray-50 rounded-xl">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No models</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by uploading a floor model above.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
