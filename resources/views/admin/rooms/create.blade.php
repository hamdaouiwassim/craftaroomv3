<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                Create Room
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur border border-emerald-100 shadow-xl sm:rounded-2xl p-8">
                @include('admin.inc.messages')

                <form method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Room Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" placeholder="e.g. Living Room, Bedroom, Kitchen" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="image" value="Room Image" />
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*,.webp"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-xs text-gray-500 mt-1">Optional room image (JPEG, PNG, WebP, GIF, SVG - max 5MB)</p>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.rooms.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all">Cancel</a>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg hover:shadow-xl transition-all">Save Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
