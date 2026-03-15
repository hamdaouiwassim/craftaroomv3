<x-customer-layout>
    @php
        $isProductRequest = $draft->request_type === 'product' && $draft->product;
        $subject = $isProductRequest ? $draft->product : $draft->concept;
        $subjectName = $subject?->name ?? ($isProductRequest ? 'Produit inconnu' : 'Concept inconnu');
        $subjectCategory = $subject?->category;
        $dimensions = $draft->normalized_requested_dimensions;
        $customizationSummary = $draft->customization_summary;
        $viewerModelType = $isProductRequest ? 'product' : 'concept';
        $viewerModelId = $subject?->id;
        $productFieldsDisabled = $isProductRequest && !$draft->product->is_resizable;
    @endphp

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.drafts.index') }}" class="p-2 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-900">Modifier le brouillon</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-blue-50/30 to-cyan-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <p class="font-semibold mb-2">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border-2 border-blue-100 overflow-hidden shadow-lg">
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-blue-100">
                            <h3 class="text-xl font-bold text-gray-900">{{ $isProductRequest ? 'Produit du brouillon' : 'Concept du brouillon' }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex gap-6">
                                <div class="w-40 h-40 flex-shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-blue-100 to-cyan-100">
                                    @if($subject && $subject->photos->count() > 0)
                                        <img src="{{ $subject->photos->first()->url }}" alt="{{ $subjectName }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-wide font-semibold text-sky-600 mb-1">{{ $isProductRequest ? 'Brouillon produit' : 'Brouillon concept' }}</p>
                                        <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $subjectName }}</h4>
                                        <p class="text-sm text-gray-500">Dernière mise à jour {{ $draft->updated_at->diffForHumans() }}</p>
                                    </div>
                                    @if($subjectCategory)
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">{{ $subjectCategory->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($viewerModelId)
                        <div class="bg-white rounded-xl border-2 border-blue-100 overflow-hidden shadow-lg js-request-viewer" @if($draft->viewer_state_json) data-viewer-state='@json($draft->viewer_state_json)' @endif>
                            <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-blue-100">
                                <h3 class="text-xl font-bold text-gray-900">Aperçu 3D enregistré</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <x-3d-viewer-original :model-type="$viewerModelType" :model-id="$viewerModelId" height="520px" />

                                @if(count($customizationSummary) > 0)
                                    <div class="p-4 bg-purple-50 border border-purple-100 rounded-lg text-sm text-purple-900">
                                        <p class="font-semibold mb-2">Détails de personnalisation enregistrés</p>
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
                                                                <p class="text-xs font-semibold text-purple-700 mb-1">Couleur</p>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="w-6 h-6 rounded-full border border-gray-300 inline-block" style="background-color: {{ $material['color'] }};"></span>
                                                                    <div>
                                                                        <p class="font-medium text-purple-950">{{ $material['color_name'] ?? 'Couleur personnalisée' }}</p>
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
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl border-2 border-blue-100 overflow-hidden shadow-lg">
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-blue-100">
                            <h3 class="text-xl font-bold text-gray-900">Mettre à jour le brouillon</h3>
                        </div>
                        <div class="p-6">
                            <form id="draftRequestForm" action="{{ route('customer.drafts.update', $draft->id) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="viewer_state_json" value="{{ $draft->viewer_state_json }}">
                                <input type="hidden" id="draft_submission_action" name="submission_action" value="draft">

                                @if($productFieldsDisabled)
                                    <div class="p-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-800">
                                        <p class="font-semibold">Ce produit n'est pas redimensionnable.</p>
                                        <p class="text-sm mt-1">La taille et les dimensions sont fixées par le producteur pour ce brouillon.</p>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="requested_size" class="block text-sm font-semibold text-gray-700 mb-2">Taille souhaitée</label>
                                        <select id="requested_size" name="requested_size" @if(!$productFieldsDisabled) required @endif @disabled($productFieldsDisabled) class="w-full border-2 border-blue-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                            <option value="" disabled {{ in_array(old('requested_size', $dimensions['size'] ?? ''), ['SMALL', 'MEDIUM', 'LARGE'], true) ? '' : 'selected' }}>Sélectionnez une taille</option>
                                            <option value="SMALL" {{ old('requested_size', $dimensions['size'] ?? '') === 'SMALL' ? 'selected' : '' }}>Small</option>
                                            <option value="MEDIUM" {{ old('requested_size', $dimensions['size'] ?? '') === 'MEDIUM' ? 'selected' : '' }}>Medium</option>
                                            <option value="LARGE" {{ old('requested_size', $dimensions['size'] ?? '') === 'LARGE' ? 'selected' : '' }}>Large</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="requested_length" class="block text-sm font-semibold text-gray-700 mb-2">Longueur</label>
                                        <input id="requested_length" name="requested_length" type="number" step="0.01" min="0" value="{{ old('requested_length', $dimensions['length'] ?? '') }}" @disabled($productFieldsDisabled) class="w-full border-2 border-blue-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label for="requested_width" class="block text-sm font-semibold text-gray-700 mb-2">Largeur</label>
                                        <input id="requested_width" name="requested_width" type="number" step="0.01" min="0" value="{{ old('requested_width', $dimensions['width'] ?? '') }}" @disabled($productFieldsDisabled) class="w-full border-2 border-blue-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label for="requested_height" class="block text-sm font-semibold text-gray-700 mb-2">Hauteur</label>
                                        <input id="requested_height" name="requested_height" type="number" step="0.01" min="0" value="{{ old('requested_height', $dimensions['height'] ?? '') }}" @disabled($productFieldsDisabled) class="w-full border-2 border-blue-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label for="requested_unit" class="block text-sm font-semibold text-gray-700 mb-2">Unité</label>
                                        <input id="requested_unit" name="requested_unit" type="text" maxlength="20" value="{{ old('requested_unit', $dimensions['unit'] ?? 'CM') }}" @disabled($productFieldsDisabled) class="w-full border-2 border-blue-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                    </div>
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                                    <textarea id="message" name="message" rows="4" class="w-full border-2 border-blue-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white resize-none">{{ old('message', $draft->message) }}</textarea>
                                </div>

                                <div>
                                    <label for="customer_notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes supplémentaires</label>
                                    <textarea id="customer_notes" name="customer_notes" rows="5" class="w-full border-2 border-blue-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white resize-none">{{ old('customer_notes', $draft->customer_notes) }}</textarea>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <button type="submit" data-submission-action="draft" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-white text-blue-700 border-2 border-blue-200 rounded-xl font-bold hover:bg-blue-50 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Enregistrer les modifications
                                    </button>
                                    <button id="draftSendButton" type="submit" data-submission-action="send" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                        Envoyer maintenant
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function isUsableViewerState(state) {
            if (!state || typeof state !== 'object') {
                return false;
            }

            if (Array.isArray(state.materials) && state.materials.length > 0) {
                return true;
            }

            return Boolean(state.model || state.floor || state.backgroundColor);
        }

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

        async function captureRequestViewerState() {
            for (let attempt = 0; attempt < 30; attempt++) {
                const viewerIframe = document.querySelector('.js-request-viewer iframe');
                const iframeWindow = viewerIframe?.contentWindow;

                if (iframeWindow && typeof iframeWindow.captureCustomizationState === 'function') {
                    try {
                        const state = await iframeWindow.captureCustomizationState();
                        if (isUsableViewerState(state)) {
                            return JSON.stringify(state);
                        }
                    } catch (error) {
                    }
                }

                await new Promise(resolve => setTimeout(resolve, 500));
            }

            return null;
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
                    alert(error.message || 'Failed to restore saved customization.');
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

        const draftRequestForm = document.getElementById('draftRequestForm');
        if (draftRequestForm) {
            draftRequestForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const actionInput = document.getElementById('draft_submission_action');
                const trigger = event.submitter;
                const submissionAction = trigger?.dataset?.submissionAction ?? 'draft';
                const viewerStateInput = draftRequestForm.querySelector('input[name="viewer_state_json"]');

                if (actionInput) {
                    actionInput.value = submissionAction;
                }

                if (trigger) {
                    trigger.disabled = true;
                    trigger.textContent = submissionAction === 'draft' ? 'Saving...' : 'Sending...';
                }

                if (typeof window.showAppLoader === 'function') {
                    window.showAppLoader(submissionAction === 'draft' ? 'Mise à jour du brouillon en cours...' : 'Envoi du brouillon en cours...');
                }

                if (viewerStateInput) {
                    viewerStateInput.value = await captureRequestViewerState() ?? viewerStateInput.value ?? '';
                }

                draftRequestForm.submit();
            });
        }
    </script>
</x-customer-layout>
