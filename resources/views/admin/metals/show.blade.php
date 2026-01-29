<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-accent rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l1-2h8l1 2h4v11H3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-500 to-blue-accent bg-clip-text text-transparent">
                        {{ $metal->name }}
                    </h2>
                    <p class="text-sm text-gray-500">Ref: {{ $metal->ref ?? '—' }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.metals.edit', $metal) }}" class="px-4 py-2 rounded-xl bg-blue-500/10 text-blue-700 font-semibold hover:bg-blue-500/20 transition-all">Edit</a>
                <a href="{{ route('admin.metals.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white/90 backdrop-blur border border-blue-100 shadow-xl sm:rounded-2xl p-6">
                @include('admin.inc.messages')
                <div class="flex items-start gap-6">
                    <div class="flex-1 space-y-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500">Reference</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $metal->ref ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500">Name</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $metal->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500">Image URL</h3>
                            <p class="text-lg font-semibold text-gray-900 break-all">{{ $metal->image_url ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="w-40 h-40 bg-gray-50 border border-blue-100 rounded-xl flex items-center justify-center overflow-hidden">
                        @if($metal->image_url)
                            <img src="{{ $metal->image_url }}" alt="{{ $metal->name }}" class="object-cover w-full h-full">
                        @else
                            <span class="text-gray-400 text-sm">No image</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur border border-blue-100 shadow-xl sm:rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Sub metals</h3>
                        <p class="text-sm text-gray-500">Add specific types/variants for this metal.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.metals.options.store', $metal) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @csrf
                    <div>
                        <x-input-label for="ref" value="Reference" />
                        <x-text-input id="ref" name="ref" type="text" class="mt-1 block w-full" value="{{ old('ref') }}" placeholder="e.g. MET-A1" />
                        <x-input-error :messages="$errors->get('ref')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="name" value="Sub metal name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="image_url" value="Image URL" />
                        <x-text-input id="image_url" name="image_url" type="url" class="mt-1 block w-full" value="{{ old('image_url') }}" placeholder="https://example.com/variant.jpg" />
                        <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-accent text-white font-semibold shadow-lg hover:shadow-xl transition-all">
                            Add sub metal
                        </button>
                    </div>
                </form>

                @if($metal->metalOptions->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-blue-50 to-sky-blue/20">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ref</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($metal->metalOptions as $option)
                                    <tr class="hover:bg-blue-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                            {{ $option->ref ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $option->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($option->image_url)
                                                <img src="{{ $option->image_url }}" alt="{{ $option->name }}" class="h-10 w-10 rounded-lg object-cover border border-blue-100">
                                            @else
                                                <span class="text-gray-400">No image</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                            <a href="{{ route('admin.metals.options.edit', [$metal, $option]) }}" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500/10 text-blue-700 hover:bg-blue-500/20 transition-all" title="Edit sub metal">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.732-6.732a2.5 2.5 0 113.536 3.536L12.536 14.5 9 15l.5-3.536z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6" />
                                                </svg>
                                                <span class="sr-only">Edit</span>
                                            </a>
                                            <form action="{{ route('admin.metals.options.destroy', [$metal, $option]) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sub metal?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-red-500/10 text-red-600 hover:bg-red-500/20 transition-all" title="Delete sub metal">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7h12z" />
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
                    <p class="text-gray-600">No sub metals yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
