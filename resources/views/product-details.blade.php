<x-main-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <!-- Product Details Header -->
    <section class="relative bg-gradient-to-br from-purple-900 via-indigo-900 to-teal-900 text-white py-12 lg:py-16 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 text-purple-200 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Back to Products</span>
                </a>
            </div>
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <h1 class="text-3xl md:text-5xl font-bold mb-2 bg-gradient-to-r from-white via-purple-100 to-indigo-100 bg-clip-text text-transparent">
                        {{ $product->name }}
                    </h1>
                    @if($product->category)
                        <p class="text-purple-200 text-lg">{{ $product->category->name }}</p>
                    @endif
                </div>
                @if($product->style_type === 'artisant')
                    <div class="md:pt-2 md:flex-shrink-0">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-400/25 via-orange-400/25 to-rose-400/25 backdrop-blur-md border border-amber-200/50 rounded-full text-sm font-bold text-amber-50 shadow-lg shadow-orange-900/20">
                            <svg class="w-4 h-4 text-amber-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Artisant
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @auth
        @if(auth()->user()->role === 2)
            <div id="productRequestModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeProductRequestModal()"></div>
                    <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 border border-purple-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Save Design &amp; Order</h3>
                            <button type="button" onclick="closeProductRequestModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <form id="productRequestForm" action="{{ route('construction-requests.store-product', $product->id) }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" id="product_viewer_state_json" name="viewer_state_json">
                            <input type="hidden" id="product_submission_action" name="submission_action" value="send">
                            @php($productFieldsDisabled = !$product->is_resizable)

                            <div class="p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200">
                                <p class="font-semibold text-purple-900 mb-1">Product: {{ $product->name }}</p>
                                <p class="text-sm text-purple-700">Your current 3D customization will be saved with this request and sent to the producer.</p>
                            </div>

                            @if($productFieldsDisabled)
                                <div class="p-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-800">
                                    <p class="font-semibold">This product is not resizable.</p>
                                    <p class="text-sm mt-1">Size and dimensions are fixed by the producer for this request.</p>
                                </div>
                            @endif

                            <div>
                                <label for="product_requested_size" class="block text-sm font-semibold text-gray-700 mb-2">Preferred Size</label>
                                <select id="product_requested_size" name="requested_size" @if(!$productFieldsDisabled) required @endif @disabled($productFieldsDisabled) class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                    <option value="" disabled {{ in_array(($product->measure?->size ?? ''), ['SMALL', 'MEDIUM', 'LARGE'], true) ? '' : 'selected' }}>Select a size</option>
                                    <option value="SMALL" {{ ($product->measure?->size ?? null) === 'SMALL' ? 'selected' : '' }}>Small</option>
                                    <option value="MEDIUM" {{ ($product->measure?->size ?? null) === 'MEDIUM' ? 'selected' : '' }}>Medium</option>
                                    <option value="LARGE" {{ ($product->measure?->size ?? null) === 'LARGE' ? 'selected' : '' }}>Large</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="requested_length" class="block text-sm font-semibold text-gray-700 mb-2">Length</label>
                                    <input id="requested_length" name="requested_length" type="number" step="0.01" min="0" value="{{ $product->measure?->dimension?->length }}" @disabled($productFieldsDisabled) class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="requested_width" class="block text-sm font-semibold text-gray-700 mb-2">Width</label>
                                    <input id="requested_width" name="requested_width" type="number" step="0.01" min="0" value="{{ $product->measure?->dimension?->width }}" @disabled($productFieldsDisabled) class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="requested_height" class="block text-sm font-semibold text-gray-700 mb-2">Height</label>
                                    <input id="requested_height" name="requested_height" type="number" step="0.01" min="0" value="{{ $product->measure?->dimension?->height }}" @disabled($productFieldsDisabled) class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="requested_unit" class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                                    <input id="requested_unit" name="requested_unit" type="text" maxlength="20" value="{{ $product->measure?->dimension?->unit ?? 'cm' }}" @disabled($productFieldsDisabled) class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                </div>
                            </div>

                            <div>
                                <label for="product_request_message" class="block text-sm font-semibold text-gray-700 mb-2">Message (Optional)</label>
                                <textarea id="product_request_message" name="message" rows="3" class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white resize-none" placeholder="Any budget, timeline, or production request details..."></textarea>
                            </div>

                            <div>
                                <label for="product_request_notes" class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes (Optional)</label>
                                <textarea id="product_request_notes" name="customer_notes" rows="3" class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white resize-none" placeholder="Special finishing, material preferences, delivery notes..."></textarea>
                            </div>

                            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                                <button type="button" onclick="closeProductRequestModal()" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">Cancel</button>
                                <button type="submit" data-submission-action="draft" class="flex-1 px-6 py-3 bg-white text-purple-700 border-2 border-purple-200 rounded-xl font-bold hover:bg-purple-50 transition-all duration-300">Save Draft</button>
                                <button id="productRequestSubmitBtn" type="submit" data-submission-action="send" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl">Send Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <!-- Product Details Content -->
    <section class="py-16 bg-gradient-to-b from-white via-purple-50/30 to-indigo-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Product Images & Media -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Main Product Images -->
                    @if($product->photos->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($product->photos->take(4) as $photo)
                                    <div class="relative group overflow-hidden rounded-xl bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100">
                                        <img src="{{ $photo->url }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer"
                                             onclick="openImageModal('{{ $photo->url }}')">
                                    </div>
                                @endforeach
                            </div>
                            @if($product->photos->count() > 4)
                                <p class="text-center text-sm text-gray-500 mt-4">
                                    +{{ $product->photos->count() - 4 }} more photos
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- 3D Model Viewer -->
                    @if($product->threedmodels)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 js-order-viewer">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">3D Model Viewer</h3>
                                <span class="ml-auto text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-semibold">Interactive</span>
                            </div>
                            
                            <x-3d-viewer-original 
                                model-type="product" 
                                :model-id="$product->id"
                                height="600px"
                            />

                            @auth
                                @if(auth()->user()->role === 2)
                                    <button
                                        type="button"
                                        onclick="openProductRequestModal()"
                                        class="mt-4 w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        Save Design &amp; Order
                                    </button>
                                @endif
                            @endauth
                        </div>
                    @endif

                    <!-- Product Specifications -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Specifications
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Size</p>
                                <p class="font-semibold text-gray-900">{{ $product->size }}</p>
                            </div>
                            @if($product->rooms->count() > 0)
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Rooms</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($product->rooms as $room)
                                            <span class="px-3 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 rounded-full text-sm font-medium">
                                                {{ $room->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($product->metals->count() > 0)
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Metals</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($product->metals as $metal)
                                            <span class="px-3 py-1 bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 rounded-full text-sm font-medium">
                                                {{ $metal->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($product->measure)
                                @if($product->measure->dimension)
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Dimensions</p>
                                        <p class="font-semibold text-gray-900">
                                            {{ $product->measure->dimension->length }} × {{ $product->measure->dimension->width }} × {{ $product->measure->dimension->height }} {{ $product->measure->dimension->unit }}
                                        </p>
                                    </div>
                                @endif
                                @if($product->measure->weight)
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Weight</p>
                                        <p class="font-semibold text-gray-900">
                                            {{ $product->measure->weight->weight_value }} {{ $product->measure->weight->weight_unit }}
                                        </p>
                                    </div>
                                @endif
                                @if($product->measure->size)
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Measure Size</p>
                                        <p class="font-semibold text-gray-900">{{ $product->measure->size }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Reel Video -->
                    @if($product->reel)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Product Reel</h3>
                            </div>
                            <div class="rounded-xl overflow-hidden bg-black">
                                <video controls class="w-full h-auto">
                                    <source src="{{ $product->reel }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    @endif

                    <!-- Product Description -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            Description
                        </h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </div>

                    <!-- Reviews Section -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Reviews ({{ $totalReviews }})
                            </h3>
                        </div>

                        <!-- Add Review Form (if user is authenticated and hasn't reviewed) -->
                        @auth
                            @if(!$userReview)
                                <div class="mb-6 p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200">
                                    <h4 class="font-bold text-gray-900 mb-3">Write a Review</h4>
                                    <form id="review-form" onsubmit="submitReview(event, {{ $product->id }})">
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                                            <div class="flex items-center gap-2" id="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" onclick="setRating({{ $i }})" class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    </button>
                                                @endfor
                                            </div>
                                            <input type="hidden" id="rating-input" name="rating" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">Comment (optional)</label>
                                            <textarea id="comment" name="comment" rows="3" class="w-full border-2 border-purple-200 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Share your thoughts about this product..."></textarea>
                                        </div>
                                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                            Submit Review
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mb-6 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                    <p class="text-sm text-gray-600 mb-2">You have already reviewed this product.</p>
                                    <div class="flex items-center gap-2 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $userReview->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    @if($userReview->comment)
                                        <p class="text-gray-700">{{ $userReview->comment }}</p>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="mb-6 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 text-center">
                                <p class="text-gray-600 mb-3">Please <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-semibold">login</a> to write a review.</p>
                            </div>
                        @endauth

                        <!-- Reviews List -->
                        <div class="space-y-4" id="reviews-container">
                            @forelse($product->reviews as $review)
                                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0 review-item" data-review-id="{{ $review->id }}">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($review->user && $review->user->avatar)
                                                <img src="{{ $review->user->avatar->url }}" alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-lg font-bold text-purple-600">{{ substr($review->user->name ?? 'U', 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h4 class="font-bold text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-gray-700">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">No reviews yet. Be the first to review this product!</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column: Product Info & Producer Profile -->
                <div class="space-y-6">
                    <!-- Product Purchase Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 sticky top-24 z-20">
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm text-gray-500">Price</span>
                                <span class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                    {{ $product->currency }}{{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            @if($product->status === 'active')
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 rounded-full text-sm font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Available
                                </span>
                            @endif
                            
                            <!-- Average Rating -->
                            @if($totalReviews > 0)
                                <div class="mt-4 flex items-center gap-2">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600">({{ number_format($averageRating, 1) }}) - {{ $totalReviews }} {{ $totalReviews === 1 ? 'review' : 'reviews' }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-3 mb-4">
                            @auth
                                @if(auth()->user()->role === 2)
                                    <button type="button" onclick="openProductRequestModal()" class="flex-1 py-4 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Save Design &amp; Order
                                    </button>
                                @else
                                    <div class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-xl font-semibold text-center">
                                        Save Design &amp; Order (Customers only)
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="flex-1 py-4 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-center">
                                    Save Design &amp; Order
                                </a>
                            @endauth
                            @auth
                                <button id="favorite-btn" onclick="toggleFavorite({{ $product->id }})" class="px-4 py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 {{ $isFavorite ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    <svg class="w-5 h-5" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            @endauth
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-3 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Handcrafted Quality</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Unique Design</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Artisan Made</span>
                            </div>
                        </div>
                    </div>

                    <!-- Producer Profile -->
                    @if($product->user)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-lg">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Producer</h3>
                                </div>
                                <a href="{{ route('producer.show', $product->user->id) }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700 flex items-center gap-1 transition-colors">
                                    View Profile
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center overflow-hidden">
                                    @if($product->user->photoUrl)
                                        <img src="{{ $product->user->photoUrl }}" alt="{{ $product->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl font-bold text-purple-600">{{ substr($product->user->name ?? 'U', 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $product->user->name ?? 'Unknown Producer' }}</h4>
                                    @if($product->user->email)
                                        <p class="text-sm text-gray-500">{{ $product->user->email }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($product->user->phone)
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $product->user->phone }}</span>
                                </div>
                            @endif

                            @if($product->user->address)
                                <div class="flex items-start gap-2 text-sm text-gray-600 mb-4">
                                    <svg class="w-4 h-4 text-purple-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        @if($product->user->address->address_line1)
                                            <div>{{ $product->user->address->address_line1 }}</div>
                                        @endif
                                        @if($product->user->address->address_line2)
                                            <div>{{ $product->user->address->address_line2 }}</div>
                                        @endif
                                        @if($product->user->address->city || $product->user->address->state || $product->user->address->country)
                                            <div>
                                                {{ $product->user->address->city }}{{ $product->user->address->city && $product->user->address->state ? ', ' : '' }}
                                                {{ $product->user->address->state }}{{ ($product->user->address->city || $product->user->address->state) && $product->user->address->country ? ', ' : '' }}
                                                {{ $product->user->address->country }}
                                            </div>
                                        @endif
                                        @if($product->user->address->postal_code)
                                            <div>{{ $product->user->address->postal_code }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($product->concept)
                                <div class="mb-4 rounded-xl border border-indigo-100 bg-indigo-50/70 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-200 to-purple-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                @if($product->concept->source === 'designer' && $product->concept->user?->photoUrl)
                                                    <img src="{{ $product->concept->user->photoUrl }}" alt="{{ $product->concept->user->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-base font-bold text-indigo-700">{{ $product->concept->source === 'designer' ? substr($product->concept->user->name ?? 'D', 0, 1) : 'C' }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Concept Owner</p>
                                                @if($product->concept->source === 'designer' && $product->concept->user)
                                                    <a href="{{ route('designer.show', $product->concept->user->id) }}" class="block truncate text-sm font-semibold text-gray-800 hover:text-indigo-700 transition-colors">
                                                        {{ $product->concept->user->name }}
                                                    </a>
                                                @else
                                                    <p class="truncate text-sm font-semibold text-gray-800">CraftARoom</p>
                                                @endif
                                                <p class="text-xs text-gray-500 truncate">Based on concept: {{ $product->concept->name }}</p>
                                            </div>
                                        </div>
                                        @if($product->concept->source === 'designer' && $product->concept->user)
                                            <a href="{{ route('designer.show', $product->concept->user->id) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                                                View Profile
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm text-gray-600 mb-2">Other Products by this Producer</p>
                                @if($producerProducts->count() > 0)
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach($producerProducts->take(4) as $producerProduct)
                                            <a href="{{ route('products.show', $producerProduct->id) }}" class="group">
                                                <div class="relative aspect-square rounded-lg overflow-hidden bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100">
                                                    @if($producerProduct->photos->count() > 0)
                                                        <img src="{{ $producerProduct->photos->first()->url }}" 
                                                             alt="{{ $producerProduct->name }}"
                                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-purple-400">
                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-700 mt-1 line-clamp-2 group-hover:text-purple-600 transition-colors">{{ $producerProduct->name }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                    @if($producerProducts->count() > 4)
                                        <a href="{{ route('products.index', ['producer' => $product->user_id]) }}" class="block text-center text-purple-600 hover:text-purple-700 font-semibold text-sm mt-3">
                                            View All ({{ $producerProducts->count() }})
                                        </a>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500">No other products available</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
    </div>

    <script>
        function openImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function openProductRequestModal() {
            const modal = document.getElementById('productRequestModal');
            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeProductRequestModal() {
            const modal = document.getElementById('productRequestModal');
            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        async function captureProductViewerState() {
            const viewerIframe = document.querySelector('.js-order-viewer iframe');
            if (!viewerIframe || !viewerIframe.contentWindow) {
                return null;
            }

            const iframeWindow = viewerIframe.contentWindow;
            if (typeof iframeWindow.captureCustomizationState !== 'function') {
                return null;
            }

            try {
                const state = await iframeWindow.captureCustomizationState();
                return state ? JSON.stringify(state) : null;
            } catch (e) {
                return null;
            }
        }

        const productRequestForm = document.getElementById('productRequestForm');
        if (productRequestForm) {
            productRequestForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const submitBtn = document.getElementById('productRequestSubmitBtn');
                const hiddenInput = document.getElementById('product_viewer_state_json');
                const actionInput = document.getElementById('product_submission_action');
                const trigger = event.submitter;
                const submissionAction = trigger?.dataset?.submissionAction ?? 'send';

                if (actionInput) {
                    actionInput.value = submissionAction;
                }

                if (trigger) {
                    trigger.disabled = true;
                    trigger.textContent = submissionAction === 'draft' ? 'Saving...' : 'Sending...';
                }

                if (submitBtn && trigger !== submitBtn) {
                    submitBtn.disabled = true;
                }

                if (typeof window.showAppLoader === 'function') {
                    window.showAppLoader(submissionAction === 'draft' ? 'Enregistrement du brouillon en cours...' : 'Envoi de votre demande en cours...');
                }

                hiddenInput.value = await captureProductViewerState() ?? '';
                productRequestForm.submit();
            });
        }

        let selectedRating = 0;

        function setRating(rating) {
            selectedRating = rating;
            document.getElementById('rating-input').value = rating;
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        async function submitReview(event, productId) {
            event.preventDefault();
            
            const rating = document.getElementById('rating-input').value;
            if (!rating) {
                showNotification('Please select a rating', 'error');
                return;
            }

            const comment = document.getElementById('comment').value;
            const formData = {
                rating: parseInt(rating),
                comment: comment || null
            };

            try {
                const response = await fetch(`/products/${productId}/reviews`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification('Review added successfully!', 'success');
                    location.reload(); // Reload to show the new review
                } else {
                    showNotification(data.message || 'Failed to add review', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }

        async function toggleFavorite(productId) {
            @guest
                window.location.href = '{{ route("login") }}';
                return;
            @endguest

            try {
                const response = await fetch(`/products/${productId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    const btn = document.getElementById('favorite-btn');
                    if (data.is_favorite) {
                        btn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                        btn.classList.add('bg-gradient-to-r', 'from-pink-500', 'to-rose-500', 'text-white');
                        btn.querySelector('svg').setAttribute('fill', 'currentColor');
                        showNotification('Added to favorites!', 'success');
                    } else {
                        btn.classList.remove('bg-gradient-to-r', 'from-pink-500', 'to-rose-500', 'text-white');
                        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                        btn.querySelector('svg').setAttribute('fill', 'none');
                        showNotification('Removed from favorites', 'success');
                    }
                } else {
                    showNotification(data.message || 'Failed to update favorite', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' 
                    : 'bg-gradient-to-r from-red-500 to-pink-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                        }
                    </svg>
                    <span class="font-semibold">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
                closeProductRequestModal();
            }
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-main-layout>

