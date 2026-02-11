<?php

namespace App\Http\Controllers;

use App\Models\ConstructionRequest;
use App\Models\ConstructionOffer;
use App\Models\Concept;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ConstructionRequestController extends Controller
{
    /**
     * Store a new construction request.
     */
    public function store(Request $request, $conceptId)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
            'customer_notes' => 'nullable|string|max:2000',
        ]);

        $concept = Concept::findOrFail($conceptId);

        // Create the construction request
        $constructionRequest = ConstructionRequest::create([
            'concept_id' => $concept->id,
            'customer_id' => auth()->id(),
            'message' => $validated['message'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'status' => 'pending',
        ]);

        // Get all constructor users (role 3)
        $constructors = User::where('role', 3)->get();

        // TODO: Send notification to all constructors
        // You can implement email or in-app notifications here
        // Example: Notification::send($constructors, new ConstructionRequestNotification($constructionRequest));

        return redirect()->route('customer.construction-requests.index')->with('success', 'Your construction request has been sent to all constructors. They will contact you soon!');
    }

    /**
     * Display construction requests for constructors.
     */
    public function index()
    {
        // Only constructors can access this
        if (auth()->user()->role !== 3) {
            abort(403, 'Unauthorized');
        }

        $requests = ConstructionRequest::with(['concept', 'concept.photos', 'concept.category', 'customer'])
            ->latest()
            ->paginate(15);

        return view('constructor.construction-requests.index', compact('requests'));
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

        $requests = ConstructionRequest::with(['concept', 'concept.photos', 'concept.category', 'offers'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('customer.construction-requests.index', compact('requests'));
    }

    /**
     * Show a specific construction request.
     */
    public function show($id)
    {
        // Only constructors can access this
        if (auth()->user()->role !== 3) {
            abort(403, 'Unauthorized');
        }

        $request = ConstructionRequest::with(['concept', 'concept.photos', 'concept.category', 'concept.rooms', 'concept.metals', 'customer', 'offers'])
            ->findOrFail($id);

        // Get current constructor's offer if exists
        $myOffer = $request->offers()->where('constructor_id', auth()->id())->first();

        return view('constructor.construction-requests.show', compact('request', 'myOffer'));
    }

    /**
     * Update the status of a construction request.
     */
    public function updateStatus(Request $request, $id)
    {
        // Only constructors can access this
        if (auth()->user()->role !== 3) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,declined,completed',
        ]);

        $constructionRequest = ConstructionRequest::findOrFail($id);
        $constructionRequest->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Request status updated successfully!');
    }

    /**
     * Submit an offer for a construction request.
     */
    public function submitOffer(Request $request, $id)
    {
        // Only constructors can access this
        if (auth()->user()->role !== 3) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'construction_time_days' => 'required|integer|min:1',
            'offer_details' => 'nullable|string|max:2000',
        ]);

        $constructionRequest = ConstructionRequest::findOrFail($id);

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

        $request = ConstructionRequest::with(['concept', 'concept.photos', 'concept.category', 'offers', 'offers.constructor'])
            ->where('customer_id', auth()->id())
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

        $constructionRequest = ConstructionRequest::where('customer_id', auth()->id())->findOrFail($requestId);
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
        // Only constructors can access this
        if (auth()->user()->role !== 3) {
            abort(403, 'Unauthorized');
        }

        $offers = ConstructionOffer::with([
            'constructionRequest',
            'constructionRequest.concept',
            'constructionRequest.concept.photos',
            'constructionRequest.concept.category',
            'constructionRequest.customer'
        ])
            ->where('constructor_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('constructor.offers.index', compact('offers'));
    }
}
