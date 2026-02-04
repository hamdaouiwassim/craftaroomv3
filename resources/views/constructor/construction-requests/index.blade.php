<x-constructor-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                Construction Requests
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-orange-50/30 to-amber-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-orange-100">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($requests->count() > 0)
                        <div class="grid gap-6">
                            @foreach($requests as $request)
                                <div class="bg-white rounded-xl border-2 border-orange-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                                    <div class="p-6">
                                        <div class="flex items-start gap-6">
                                            <!-- Concept Image -->
                                            <div class="w-32 h-32 flex-shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-orange-100 to-amber-100">
                                                @if($request->concept && $request->concept->photos->count() > 0)
                                                    <img src="{{ $request->concept->photos->first()->url }}" 
                                                         alt="{{ $request->concept->name }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Request Info -->
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between mb-3">
                                                    <div>
                                                        <h3 class="text-xl font-bold text-gray-900 mb-1">
                                                            {{ $request->concept->name ?? 'Unknown Concept' }}
                                                        </h3>
                                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                {{ $request->customer->name ?? 'Unknown Customer' }}
                                                            </span>
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                {{ $request->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @if($request->status === 'pending')
                                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">Pending</span>
                                                        @elseif($request->status === 'accepted')
                                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Accepted</span>
                                                        @elseif($request->status === 'declined')
                                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">Declined</span>
                                                        @else
                                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Completed</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($request->message)
                                                    <div class="mb-3 p-3 bg-orange-50/50 rounded-lg border border-orange-100">
                                                        <p class="text-sm text-gray-700">{{ Str::limit($request->message, 150) }}</p>
                                                    </div>
                                                @endif

                                                @if($request->concept->category)
                                                    <span class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 rounded text-xs font-medium">
                                                        {{ $request->concept->category->name }}
                                                    </span>
                                                @endif

                                                <div class="mt-4 flex items-center gap-3">
                                                    <a href="{{ route('constructor.construction-requests.show', $request->id) }}" 
                                                       class="px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-lg font-semibold hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                        View Details
                                                    </a>
                                                    @if($request->customer->phone)
                                                        <a href="tel:{{ $request->customer->phone }}" class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors text-sm flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            Call
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-20 bg-white rounded-xl border border-orange-100">
                            <svg class="w-24 h-24 text-orange-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Construction Requests Yet</h3>
                            <p class="text-gray-600">New construction requests from customers will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-constructor-layout>
