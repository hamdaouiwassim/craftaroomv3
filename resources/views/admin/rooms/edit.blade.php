<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                Edit Room: {{ $room->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur border border-emerald-100 shadow-xl sm:rounded-2xl p-8">
                @include('admin.inc.messages')

                <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Room Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $room->name) }}" placeholder="e.g. Living Room, Bedroom, Kitchen" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="image" value="Room Image" />
                        @if($room->image)
                            <div class="mb-3">
                                <img src="{{ $room->image }}" alt="{{ $room->name }}" class="h-20 w-20 object-cover rounded-lg">
                                <p class="text-xs text-gray-500 mt-1">Current image</p>
                            </div>
                        @endif
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*,.webp"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-xs text-gray-500 mt-1">Upload new image (JPEG, PNG, WebP, GIF, SVG - max 5MB). Leave empty to keep current.</p>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            <p>Room ID: {{ $room->id }}</p>
                            <p>Created: {{ $room->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.rooms.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all">Cancel</a>
                            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg hover:shadow-xl transition-all">Update Room</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
