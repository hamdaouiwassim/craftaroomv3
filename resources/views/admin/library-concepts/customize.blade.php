<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.library-concepts.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="p-2 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                    Personnalisation : {{ $concept->name }}
                </h2>
            </div>
            <a href="{{ route('admin.library-concepts.show', $concept) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                Voir le concept
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-teal-50/30 to-cyan-50/30 overflow-hidden shadow-xl sm:rounded-2xl border border-teal-100">
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <p class="text-gray-600 mb-8">Choisissez une ou plusieurs options (sous-métaux) pour chaque matériau sélectionné sur ce concept.</p>

                <form action="{{ route('admin.library-concepts.save-customize', $concept) }}" method="POST" class="space-y-8">
                    @csrf

                    @forelse($concept->metals as $metal)
                        <div class="bg-white/80 rounded-xl p-6 border-2 border-teal-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                                @if($metal->image_url)
                                    <img src="{{ $metal->image_url }}" alt="" class="w-8 h-8 rounded-lg object-cover">
                                @else
                                    <span class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                    </span>
                                @endif
                                {{ $metal->name }}
                                @if($metal->ref)
                                    <span class="text-sm font-normal text-gray-500">({{ $metal->ref }})</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Sélectionnez un ou plusieurs types / sous-options pour ce matériau</p>

                            @if($metal->metalOptions->count() > 0)
                                @php
                                    $selectedIds = $selectedOptionsByMetal->get($metal->id) ?? collect();
                                @endphp
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($metal->metalOptions as $option)
                                        @php
                                            $isSelected = $selectedIds->contains((int) $option->id);
                                        @endphp
                                        <label class="relative flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all {{ $isSelected ? 'border-teal-500 bg-teal-50/50 ring-2 ring-teal-200' : 'border-gray-200 hover:border-teal-300 hover:bg-teal-50/30' }}">
                                            <input type="checkbox"
                                                   name="options[{{ $metal->id }}][]"
                                                   value="{{ $option->id }}"
                                                   class="mt-1 h-4 w-4 rounded text-teal-600 border-gray-300 focus:ring-teal-500"
                                                   {{ $isSelected ? 'checked' : '' }}>
                                            <div class="flex-1 min-w-0">
                                                @if($option->image_url)
                                                    <img src="{{ $option->image_url }}" alt="{{ $option->name }}" class="w-full h-24 object-cover rounded-lg mb-2">
                                                @endif
                                                <span class="font-semibold text-gray-900 block">{{ $option->name }}</span>
                                                @if($option->ref)
                                                    <span class="text-xs text-gray-500">{{ $option->ref }}</span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-amber-600 text-sm">Aucune sous-option disponible pour ce matériau. Vous pouvez en ajouter depuis l’admin.</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500">Aucun matériau associé à ce concept. Modifiez le concept pour en ajouter.</p>
                    @endforelse

                    @if($concept->metals->count() > 0)
                        <div class="flex justify-end gap-4 pt-6 border-t border-teal-100">
                            <a href="{{ route('admin.library-concepts.show', $concept) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300">
                                Annuler
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl font-bold hover:from-teal-600 hover:to-cyan-600 shadow-lg">
                                Enregistrer la personnalisation
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
