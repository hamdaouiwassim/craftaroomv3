<x-customer-layout>
    @php
        $isProductRequest = $request->request_type === 'product' && $request->product;
        $subject = $isProductRequest ? $request->product : $request->concept;
        $subjectName = $subject?->name ?? ($isProductRequest ? 'Produit inconnu' : 'Concept inconnu');
        $subjectCategory = $subject?->category;
        $subjectRooms = $subject?->rooms ?? collect();
        $subjectMetals = $subject?->metals ?? collect();
        $viewerModelType = $isProductRequest ? 'product' : 'concept';
        $viewerModelId = $subject?->id;
        $dimensions = $request->normalized_requested_dimensions;
        $customizationSummary = $request->customization_summary;
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.construction-requests.index') }}" class="p-2 bg-green-100 hover:bg-green-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-bold text-2xl text-gray-900">Détail de la Demande</h2>
            </div>
            <div>
                @if($request->status === 'pending')
                    <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">En attente</span>
                @elseif($request->status === 'accepted')
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Acceptée</span>
                @elseif($request->status === 'declined')
                    <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full text-sm font-semibold">Refusée</span>
                @elseif($request->status === 'canceled')
                    <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">Annulée</span>
                @else
                    <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Terminée</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border-2 border-green-100 overflow-hidden shadow-lg">
                        <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
                            <h3 class="text-xl font-bold text-gray-900">{{ $isProductRequest ? 'Produit demandé' : 'Concept demandé' }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex gap-6">
                                <div class="w-48 h-48 flex-shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-green-100 to-emerald-100">
                                    @if($subject && $subject->photos->count() > 0)
                                        <img src="{{ $subject->photos->first()->url }}" alt="{{ $subjectName }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 space-y-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-wide font-semibold text-emerald-600 mb-1">{{ $isProductRequest ? 'Product Request' : 'Concept Request' }}</p>
                                        <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $subjectName }}</h4>
                                        <p class="text-sm text-gray-500">Envoyée {{ ($request->submitted_at ?? $request->created_at)->diffForHumans() }}</p>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        @if($subjectCategory)
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">{{ $subjectCategory->name }}</span>
                                        @endif
                                        @if(!$isProductRequest)
                                            <span class="px-3 py-1 {{ $request->concept && $request->concept->source === 'designer' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700' }} rounded-full text-sm font-semibold">{{ $request->concept && $request->concept->source === 'designer' ? 'Designer Concept' : 'Library Concept' }}</span>
                                        @else
                                            <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm font-semibold">Direct to Producer</span>
                                        @endif
                                    </div>

                                    @if($subjectRooms->count() > 0)
                                        <div>
                                            <p class="text-sm text-gray-600 mb-2">Pièces</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($subjectRooms as $room)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ $room->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($subjectMetals->count() > 0)
                                        <div>
                                            <p class="text-sm text-gray-600 mb-2">Matériaux</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($subjectMetals as $metal)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $metal->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap gap-3 pt-2">
                                        @if($isProductRequest && $request->product)
                                            <a href="{{ route('products.show', $request->product->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Voir le produit standard
                                            </a>
                                        @elseif(!$isProductRequest && $request->concept)
                                            <a href="{{ route('concepts.show', $request->concept->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Voir le concept standard
                                            </a>
                                        @endif

                                        @if($request->canBeEditedByCustomer())
                                            <a href="{{ route('customer.construction-requests.edit', $request->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg font-semibold hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier la demande
                                            </a>
                                        @endif

                                        <a href="{{ route('customer.construction-requests.offers', $request->id) }}" class="inline-flex items-center gap-2 px-4 py-2 {{ $request->offers->count() > 0 ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : 'bg-gray-400' }} text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 shadow-md text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Offres
                                            @if($request->offers->count() > 0)
                                                <span class="px-2 py-0.5 bg-white text-blue-600 rounded-full text-xs font-bold">{{ $request->offers->count() }}</span>
                                            @endif
                                        </a>

                                        @if($request->canBeCanceledByCustomer())
                                            <form action="{{ route('customer.construction-requests.cancel', $request->id) }}" method="POST" onsubmit="return confirm('Annuler cette demande ? Vous pourrez ensuite en créer une nouvelle.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg font-semibold hover:from-red-600 hover:to-rose-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Annuler la demande
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($viewerModelId)
                        <div class="bg-white rounded-xl border-2 border-green-100 overflow-hidden shadow-lg js-request-viewer" @if($request->viewer_state_json) data-viewer-state='@json($request->viewer_state_json)' @endif>
                            <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
                                <h3 class="text-xl font-bold text-gray-900">3D Customization Preview</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <x-3d-viewer-original :model-type="$viewerModelType" :model-id="$viewerModelId" height="520px" />

                                @if($dimensions)
                                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-900">
                                        <p class="font-semibold mb-1">Requested dimensions</p>
                                        <p>{{ $dimensions['length'] ?? '-' }} × {{ $dimensions['width'] ?? '-' }} × {{ $dimensions['height'] ?? '-' }} {{ $dimensions['unit'] ?? '' }}</p>
                                    </div>
                                @endif

                                @if(count($customizationSummary) > 0)
                                    <div class="p-4 bg-purple-50 border border-purple-100 rounded-lg text-sm text-purple-900">
                                        <p class="font-semibold mb-2">Saved customization details</p>
                                        <div class="space-y-2">
                                            @foreach($customizationSummary as $material)
                                                <div class="rounded-lg border border-purple-100 bg-white/70 p-3">
                                                    <p class="font-semibold text-purple-950 mb-2">{{ $material['name'] }}</p>
                                                    <div class="flex flex-wrap gap-4 items-start">
                                                        @if($material['texture_url'])
                                                            <div>
                                                                <p class="text-xs font-semibold text-purple-700 mb-1">Texture</p>
                                                                <a href="{{ $material['texture_url'] }}" target="_blank" rel="noopener noreferrer" class="block group">
                                                                    <img src="{{ $material['texture_url'] }}" alt="{{ $material['texture'] ?? $material['name'] }}" class="w-20 h-20 rounded-lg border border-purple-200 object-cover group-hover:scale-105 transition-transform duration-200 shadow-sm">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if($material['color'])
                                                            <div>
                                                                <p class="text-xs font-semibold text-purple-700 mb-1">Color</p>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="w-6 h-6 rounded-full border border-gray-300 inline-block" style="background-color: {{ $material['color'] }};"></span>
                                                                    <div>
                                                                        <p class="font-medium text-purple-950">{{ $material['color_name'] ?? 'Custom color' }}</p>
                                                                        <p class="text-xs text-purple-700">{{ $material['color'] }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($request->message || $request->customer_notes)
                        <div class="bg-white rounded-xl border-2 border-green-100 overflow-hidden shadow-lg">
                            <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
                                <h3 class="text-xl font-bold text-gray-900">Votre message</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                @if($request->message)
                                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                                        <p class="text-sm font-semibold text-green-800 mb-2">Message</p>
                                        <p class="text-gray-700">{{ $request->message }}</p>
                                    </div>
                                @endif
                                @if($request->customer_notes)
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                        <p class="text-sm font-semibold text-blue-800 mb-2">Additional notes</p>
                                        <p class="text-gray-700">{{ $request->customer_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl border-2 border-green-100 overflow-hidden shadow-lg">
                        <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
                            <h3 class="text-xl font-bold text-gray-900">Informations de la demande</h3>
                        </div>
                        <div class="p-6 space-y-4 text-sm text-gray-700">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Type</p>
                                <p class="font-semibold text-gray-900">{{ $isProductRequest ? 'Product Request' : 'Concept Request' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Envoyée le</p>
                                <p class="font-semibold text-gray-900">{{ ($request->submitted_at ?? $request->created_at)->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Nombre d'offres</p>
                                <p class="font-semibold text-gray-900">{{ $request->offers->count() }}</p>
                            </div>
                            @if($request->acceptedOffer())
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Offre acceptée</p>
                                    <p class="font-semibold text-green-700">{{ $request->acceptedOffer()->constructor->name ?? 'Accepted constructor' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function tryRestoreCustomization(payload, maxAttempts = 30) {
            for (let attempt = 0; attempt < maxAttempts; attempt++) {
                const viewerIframe = document.querySelector('.js-request-viewer iframe');
                const iframeWindow = viewerIframe?.contentWindow;

                if (iframeWindow && typeof iframeWindow.restoreCustomizationState === 'function') {
                    return iframeWindow.restoreCustomizationState(payload);
                }

                await new Promise(resolve => setTimeout(resolve, 500));
            }

            return false;
        }

        async function loadRequestCustomization(viewerStateJson, showError = true) {
            try {
                const payload = typeof viewerStateJson === 'string' ? JSON.parse(viewerStateJson) : viewerStateJson;
                const success = await tryRestoreCustomization(payload);

                if (!success && showError) {
                    alert('Failed to restore saved customization.');
                }

                return success;
            } catch (error) {
                console.error('Restore customization error:', error);
                if (showError) {
                    alert(error.message || 'Failed to restore customization.');
                }
                return false;
            }
        }

        window.addEventListener('load', function () {
            const container = document.querySelector('.js-request-viewer');
            const rawState = container?.dataset?.viewerState;

            if (rawState) {
                loadRequestCustomization(rawState, false);
            }
        });
    </script>
</x-customer-layout>
