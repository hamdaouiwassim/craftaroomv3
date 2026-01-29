<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-accent rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-500 to-blue-accent bg-clip-text text-transparent">
                Create Metal
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur border border-blue-100 shadow-xl sm:rounded-2xl p-8">
                @include('admin.inc.messages')

                <form method="POST" action="{{ route('admin.metals.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="ref" value="Reference" />
                        <x-text-input id="ref" name="ref" type="text" class="mt-1 block w-full" value="{{ old('ref') }}" placeholder="e.g. MET-001" />
                        <x-input-error :messages="$errors->get('ref')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="image_url" value="Image URL" />
                        <x-text-input id="image_url" name="image_url" type="url" class="mt-1 block w-full" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" />
                        <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.metals.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all">Cancel</a>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-accent text-white font-semibold shadow-lg hover:shadow-xl transition-all">Save Metal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
