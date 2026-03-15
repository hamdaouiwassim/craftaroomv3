<?php

namespace App\Http\Controllers;

use App\Models\ConstructionRequest;
use App\Models\ConstructionOffer;
use App\Models\Concept;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ConstructionRequestController extends Controller
{
    /**
     * Store a new construction request.
     */
    public function store(Request $request, Concept $concept)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $validated = $this->validateRequestPayload($request, true);
        $status = $this->resolveRequestedStatus($request);

        ConstructionRequest::create([
            'request_type' => 'concept',
            'concept_id' => $concept->id,
            'product_id' => null,
            'customer_id' => auth()->id(),
            'target_producer_id' => null,
            'message' => $validated['message'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'viewer_state_json' => $validated['viewer_state_json'],
            'requested_dimensions_json' => $this->extractDimensions($validated),
            'status' => $status,
            'submitted_at' => $status === 'draft' ? null : now(),
        ]);

        if ($status === 'draft') {
            return redirect()
                ->route('customer.drafts.index')
                ->with('success', 'Votre brouillon a été enregistré.');
        }

        return redirect()->route('customer.construction-requests.index')->with('success', 'Your construction request has been sent to all constructors. They will contact you soon!');
    }

    /**
     * Store a direct product request to the owning producer.
     */
    public function storeForProduct(Request $request, Product $product)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $validated = $this->validateRequestPayload($request, $product->is_resizable);
        $status = $this->resolveRequestedStatus($request);

        ConstructionRequest::create([
            'request_type' => 'product',
            'concept_id' => $product->concept_id ?? null,
            'product_id' => $product->id,
            'customer_id' => auth()->id(),
            'target_producer_id' => $product->user_id,
            'message' => $validated['message'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'viewer_state_json' => $validated['viewer_state_json'],
            'requested_dimensions_json' => $this->extractDimensions($validated),
            'status' => $status,
            'submitted_at' => $status === 'draft' ? null : now(),
        ]);

        if ($status === 'draft') {
            return redirect()
                ->route('customer.drafts.index')
                ->with('success', 'Votre brouillon a été enregistré.');
        }

        return redirect()
            ->route('customer.construction-requests.index')
            ->with('success', 'Your design request has been sent to the producer.');
    }

    /**
     * Display construction requests for constructors.
     */
    public function index()
    {
        // Only constructors/admins can access this
        if (!in_array(auth()->user()->role, [0, 3], true)) {
            abort(403, 'Unauthorized');
        }

        $query = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'product',
                'product.photos',
                'product.category',
                'customer',
                'targetProducer',
            ]);

        $query->whereNotIn('status', ['canceled', 'draft']);

        if (auth()->user()->role === 0) {
            // Admin sees ALL requests
        } else {
            // Constructor sees concept requests (open bidding) + product requests targeted to them
            $query->where(function ($q) {
                $q->where('request_type', 'concept')
                  ->orWhere('target_producer_id', auth()->id());
            });
        }

        $requests = $query
            ->orderByRaw('COALESCE(submitted_at, created_at) DESC')
            ->orderByDesc('created_at')
            ->paginate(15);

        $view = auth()->user()->role === 0
            ? 'admin.construction-requests.index'
            : 'constructor.construction-requests.index';

        return view($view, compact('requests'));
    }

    /**
     * Display construction requests for customers (their own requests).
     */
    public function customerIndex()
    {
        // Only customers can access this
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $requests = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'product',
                'product.photos',
                'product.category',
                'offers',
            ])
            ->where('customer_id', auth()->id())
            ->where('status', '!=', 'draft')
            ->orderByRaw('COALESCE(submitted_at, created_at) DESC')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('customer.construction-requests.index', compact('requests'));
    }

    /**
     * Display draft construction requests for customers.
     */
    public function customerDraftIndex()
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $drafts = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'product',
                'product.photos',
                'product.category',
            ])
            ->where('customer_id', auth()->id())
            ->where('status', 'draft')
            ->latest()
            ->paginate(15);

        return view('customer.construction-requests.drafts', compact('drafts'));
    }

    /**
     * Edit a customer draft.
     */
    public function editDraft($id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $draft = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'concept.rooms',
                'concept.metals',
                'product',
                'product.photos',
                'product.category',
                'product.rooms',
                'product.metals',
                'targetProducer',
            ])
            ->where('customer_id', auth()->id())
            ->where('status', 'draft')
            ->findOrFail($id);

        return view('customer.construction-requests.edit-draft', compact('draft'));
    }

    /**
     * Update a customer draft.
     */
    public function updateDraft(Request $request, $id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $draft = ConstructionRequest::where('customer_id', auth()->id())
            ->where('status', 'draft')
            ->findOrFail($id);

        $requiresSize = $draft->request_type === 'concept' || (bool) optional($draft->product)->is_resizable;

        $validated = $this->validateRequestPayload($request, $requiresSize);
        $status = $this->resolveRequestedStatus($request);

        $draft->update([
            'message' => $validated['message'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'viewer_state_json' => $validated['viewer_state_json'],
            'requested_dimensions_json' => $this->extractDimensions($validated),
            'status' => $status,
            'submitted_at' => $status === 'draft' ? null : now(),
        ]);

        if ($status === 'draft') {
            return redirect()
                ->route('customer.drafts.index')
                ->with('success', 'Votre brouillon a été mis à jour.');
        }

        return redirect()
            ->route('customer.construction-requests.index')
            ->with('success', 'Votre brouillon a été envoyé avec succès.');
    }

    /**
     * Edit a customer sent request.
     */
    public function editCustomerRequest($id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $request = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'concept.rooms',
                'concept.metals',
                'product',
                'product.photos',
                'product.category',
                'product.rooms',
                'product.metals',
                'targetProducer',
                'offers',
            ])
            ->where('customer_id', auth()->id())
            ->where('status', '!=', 'draft')
            ->findOrFail($id);

        if (!$request->canBeEditedByCustomer()) {
            return redirect()
                ->route('customer.construction-requests.show', $request->id)
                ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        return view('customer.construction-requests.edit-request', compact('request'));
    }

    /**
     * Update a customer sent request.
     */
    public function updateCustomerRequest(Request $request, $id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $constructionRequest = ConstructionRequest::with('offers')
            ->where('customer_id', auth()->id())
            ->where('status', '!=', 'draft')
            ->findOrFail($id);

        if (!$constructionRequest->canBeEditedByCustomer()) {
            return redirect()
                ->route('customer.construction-requests.show', $constructionRequest->id)
                ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        $requiresSize = $constructionRequest->request_type === 'concept' || (bool) optional($constructionRequest->product)->is_resizable;

        $validated = $this->validateRequestPayload($request, $requiresSize);

        $constructionRequest->update([
            'message' => $validated['message'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'viewer_state_json' => $validated['viewer_state_json'],
            'requested_dimensions_json' => $this->extractDimensions($validated),
            'status' => 'pending',
            'submitted_at' => $constructionRequest->submitted_at ?? now(),
        ]);

        if ($constructionRequest->offers()->exists()) {
            $constructionRequest->offers()->update(['status' => 'rejected']);
        }

        return redirect()
            ->route('customer.construction-requests.show', $constructionRequest->id)
            ->with('success', 'Votre demande a été mise à jour avec succès.');
    }

    /**
     * Send an existing draft.
     */
    public function sendDraft($id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $draft = ConstructionRequest::where('customer_id', auth()->id())
            ->where('status', 'draft')
            ->findOrFail($id);

        $draft->update([
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('customer.construction-requests.index')
            ->with('success', 'Votre brouillon a été envoyé avec succès.');
    }

    /**
     * Delete a customer draft.
     */
    public function destroyDraft($id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $draft = ConstructionRequest::where('customer_id', auth()->id())
            ->where('status', 'draft')
            ->findOrFail($id);

        $draft->delete();

        return redirect()
            ->route('customer.drafts.index')
            ->with('success', 'Votre brouillon a été supprimé.');
    }

    /**
     * Display a specific construction request for the customer who created it.
     */
    public function customerShow($id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $request = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'concept.rooms',
                'concept.metals',
                'product',
                'product.photos',
                'product.category',
                'product.rooms',
                'product.metals',
                'targetProducer',
                'offers',
                'offers.constructor',
            ])
            ->where('customer_id', auth()->id())
            ->where('status', '!=', 'draft')
            ->findOrFail($id);

        return view('customer.construction-requests.show', compact('request'));
    }

    /**
     * Cancel a customer construction request.
     */
    public function customerCancel($id)
    {
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $constructionRequest = ConstructionRequest::with('offers')
            ->where('customer_id', auth()->id())
            ->where('status', '!=', 'draft')
            ->findOrFail($id);

        if (in_array($constructionRequest->status, ['accepted', 'completed'], true)) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus être annulée.');
        }

        $constructionRequest->update(['status' => 'canceled']);
        $constructionRequest->offers()->update(['status' => 'rejected']);

        return redirect()
            ->route('customer.construction-requests.index')
            ->with('success', 'Votre demande a été annulée. Vous pouvez créer une nouvelle demande si nécessaire.');
    }

    /**
     * Show a specific construction request.
     */
    public function show($id)
    {
        // Only constructors/admins can access this
        if (!in_array(auth()->user()->role, [0, 3], true)) {
            abort(403, 'Unauthorized');
        }

        $request = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'concept.rooms',
                'concept.metals',
                'product',
                'product.photos',
                'product.category',
                'product.rooms',
                'product.metals',
                'targetProducer',
                'customer',
                'offers',
            ])
            ->findOrFail($id);

        if ($request->status === 'draft') {
            abort(404);
        }

        if (!$this->isProducerAllowedForRequest($request)) {
            abort(403, 'Unauthorized');
        }

        // Get current constructor's offer if exists
        $myOffer = $request->offers()->where('constructor_id', auth()->id())->first();

        $view = auth()->user()->role === 0
            ? 'admin.construction-requests.show'
            : 'constructor.construction-requests.show';

        return view($view, compact('request', 'myOffer'));
    }

    /**
     * Update the status of a construction request.
     */
    public function updateStatus(Request $request, $id)
    {
        // Only constructors/admins can access this
        if (!in_array(auth()->user()->role, [0, 3], true)) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,declined,completed',
        ]);

        $constructionRequest = ConstructionRequest::where('status', '!=', 'draft')->findOrFail($id);
        $constructionRequest->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Request status updated successfully!');
    }

    /**
     * Submit an offer for a construction request.
     */
    public function submitOffer(Request $request, $id)
    {
        // Only constructors/admins can access this
        if (!in_array(auth()->user()->role, [0, 3], true)) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'construction_time_days' => 'required|integer|min:1',
            'offer_details' => 'nullable|string|max:2000',
        ]);

        $constructionRequest = ConstructionRequest::where('status', '!=', 'draft')->findOrFail($id);

        if (!$this->isProducerAllowedForRequest($constructionRequest)) {
            abort(403, 'Unauthorized');
        }

        // Check if constructor already has an offer
        $existingOffer = ConstructionOffer::where('construction_request_id', $id)
            ->where('constructor_id', auth()->id())
            ->first();

        if ($existingOffer) {
            // Update existing offer
            $existingOffer->update([
                'price' => $validated['price'],
                'construction_time_days' => $validated['construction_time_days'],
                'offer_details' => $validated['offer_details'] ?? null,
            ]);
            $message = 'Your offer has been updated successfully!';
        } else {
            // Create new offer
            ConstructionOffer::create([
                'construction_request_id' => $id,
                'constructor_id' => auth()->id(),
                'price' => $validated['price'],
                'construction_time_days' => $validated['construction_time_days'],
                'offer_details' => $validated['offer_details'] ?? null,
            ]);
            $message = 'Your offer has been submitted successfully!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show offers for a specific construction request (for customers).
     */
    public function showOffers($id)
    {
        // Only customers can access this
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $request = ConstructionRequest::with([
                'concept',
                'concept.photos',
                'concept.category',
                'product',
                'product.photos',
                'product.category',
                'offers',
                'offers.constructor',
            ])
            ->where('customer_id', auth()->id())
            ->where('status', '!=', 'draft')
            ->findOrFail($id);

        return view('customer.construction-requests.offers', compact('request'));
    }

    /**
     * Accept an offer.
     */
    public function acceptOffer($requestId, $offerId)
    {
        // Only customers can access this
        if (auth()->user()->role !== 2) {
            abort(403, 'Unauthorized');
        }

        $constructionRequest = ConstructionRequest::where('customer_id', auth()->id())
            ->where('status', '!=', 'draft')
            ->findOrFail($requestId);
        $offer = ConstructionOffer::where('construction_request_id', $requestId)->findOrFail($offerId);

        // Reject all other offers
        ConstructionOffer::where('construction_request_id', $requestId)
            ->where('id', '!=', $offerId)
            ->update(['status' => 'rejected']);

        // Accept this offer
        $offer->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Update request status
        $constructionRequest->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'Offer accepted successfully! The constructor will be notified.');
    }

    /**
     * Display all offers made by the current constructor.
     */
    public function myOffers()
    {
        // Only constructors/admins can access this
        if (!in_array(auth()->user()->role, [0, 3], true)) {
            abort(403, 'Unauthorized');
        }

        $offers = ConstructionOffer::with([
            'constructionRequest',
            'constructionRequest.concept',
            'constructionRequest.concept.photos',
            'constructionRequest.concept.category',
            'constructionRequest.product',
            'constructionRequest.product.photos',
            'constructionRequest.product.category',
            'constructionRequest.customer'
        ])
            ->where('constructor_id', auth()->id())
            ->latest()
            ->paginate(15);

        $view = auth()->user()->role === 0
            ? 'admin.offers.index'
            : 'constructor.offers.index';

        return view($view, compact('offers'));
    }

    private function validateRequestPayload(Request $request, bool $requireSize = true): array
    {
        return $request->validate([
            'message' => 'nullable|string|max:1000',
            'customer_notes' => 'nullable|string|max:2000',
            'viewer_state_json' => 'nullable|string|max:200000',
            'submission_action' => 'nullable|in:draft,send',
            'requested_size' => ($requireSize ? 'required' : 'nullable') . '|in:SMALL,MEDIUM,LARGE',
            'requested_length' => 'nullable|numeric|min:0',
            'requested_width' => 'nullable|numeric|min:0',
            'requested_height' => 'nullable|numeric|min:0',
            'requested_unit' => 'nullable|string|max:20',
        ]);
    }

    private function resolveRequestedStatus(Request $request): string
    {
        return $request->input('submission_action') === 'draft' ? 'draft' : 'pending';
    }

    private function extractDimensions(array $validated): ?array
    {
        $normalizeNumber = static function ($value) {
            if ($value === null || $value === '') {
                return null;
            }

            return is_numeric($value) ? (float) $value : null;
        };

        $dimensionPayload = [
            'size' => filled($validated['requested_size'] ?? null) ? strtoupper(trim((string) $validated['requested_size'])) : null,
            'length' => $normalizeNumber($validated['requested_length'] ?? null),
            'width' => $normalizeNumber($validated['requested_width'] ?? null),
            'height' => $normalizeNumber($validated['requested_height'] ?? null),
            'unit' => filled($validated['requested_unit'] ?? null) ? strtoupper(trim((string) $validated['requested_unit'])) : null,
        ];

        if ($dimensionPayload['size'] === null &&
            $dimensionPayload['length'] === null &&
            $dimensionPayload['width'] === null &&
            $dimensionPayload['height'] === null &&
            $dimensionPayload['unit'] === null) {
            return null;
        }

        return $dimensionPayload;
    }

    private function isProducerAllowedForRequest(ConstructionRequest $constructionRequest): bool
    {
        // Admin can see any request
        if (auth()->user()->role === 0) {
            return true;
        }

        if ($constructionRequest->request_type === 'product') {
            return (int) $constructionRequest->target_producer_id === (int) auth()->id();
        }

        return true;
    }
}
