<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.732-6.732a2.5 2.5 0 113.536 3.536L12.536 14.5 9 15l.5-3.536z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                Edit Floor
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur border border-teal-100 shadow-xl sm:rounded-2xl p-8">
                @include('admin.inc.messages')

                <form method="POST" action="{{ route('admin.floors.update', $floor) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Floor Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $floor->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="icon" value="Floor Icon (Optional)" />
                        
                        @if($floor->icon)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">Current Icon:</p>
                                <img src="{{ asset('storage/' . $floor->icon) }}" alt="{{ $floor->name }}" class="h-20 w-20 rounded-lg object-cover border border-teal-100">
                            </div>
                        @endif

                        <input id="icon" 
                               name="icon" 
                               type="file" 
                               accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-teal-500" />
                        <p class="mt-1 text-sm text-gray-500">Supported formats: JPEG, PNG, GIF, SVG (Max: 2MB). Leave empty to keep current icon.</p>
                        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.floors.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all">Cancel</a>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold shadow-lg hover:shadow-xl transition-all">Update Floor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
