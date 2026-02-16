<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                    Manage Floors
                </h2>
            </div>
            <a href="{{ route('admin.floors.create') }}" class="flex items-center gap-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-6 py-3 rounded-xl font-bold hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
                New Floor
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur border border-teal-500/20 shadow-xl sm:rounded-2xl overflow-hidden">
                <div class="p-6">
                    @include('admin.inc.messages')

                    <div class="mb-6">
                        <form method="GET" action="{{ route('admin.floors.index') }}" class="flex gap-4">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by name..."
                                       class="block w-full pl-12 pr-4 py-3 border-2 border-teal-200 rounded-xl shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white text-gray-900 placeholder-gray-400">
                            </div>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl font-semibold hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                Search
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.floors.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    @if($floors->count())
                        <div class="bg-white rounded-xl shadow-sm border border-teal-100 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-teal-50 to-cyan-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Icon</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Models</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($floors as $floor)
                                            <tr class="hover:bg-teal-50/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($floor->icon)
                                                        <img src="{{ asset('storage/' . $floor->icon) }}" alt="{{ $floor->name }}" class="h-10 w-10 rounded-lg object-cover border border-teal-100">
                                                    @else
                                                        <span class="text-gray-400">No icon</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                                    {{ $floor->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $floor->floor_models_count }} model(s)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                                    <a href="{{ route('admin.floors.show', $floor) }}" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-teal-500/10 text-teal-600 hover:bg-teal-500/20 transition-all" title="View">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        <span class="sr-only">View</span>
                                                    </a>
                                                    <a href="{{ route('admin.floors.edit', $floor) }}" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-cyan-500/10 text-cyan-700 hover:bg-cyan-500/20 transition-all" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.732-6.732a2.5 2.5 0 113.536 3.536L12.536 14.5 9 15l.5-3.536z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6" />
                                                        </svg>
                                                        <span class="sr-only">Edit</span>
                                                    </a>
                                                    <form action="{{ route('admin.floors.destroy', $floor) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this floor and all its models?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-red-500/10 text-red-600 hover:bg-red-500/20 transition-all" title="Delete">
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
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                {{ $floors->links('vendor.pagination.products') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-600 text-lg">No floors found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
