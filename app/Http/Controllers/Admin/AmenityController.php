<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Amenity;
use App\Services\AmenityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AmenityController extends Controller
{
    protected AmenityService $amenityService;

    /**
     * Constructor.
     */
    public function __construct(AmenityService $amenityService)
    {
        $this->amenityService = $amenityService;
    }

    /**
     * Display a listing of amenities.
     */
    public function index(): View
    {
        $amenities = $this->amenityService->getAll();

        return view('dashboard.amenities.index', compact('amenities'));
    }

    /**
     * Show the form for creating a new amenity.
     */
    public function create(): View
    {
        return view('dashboard.amenities.create');
    }

    /**
     * Store a newly created amenity.
     */
    public function store(StoreAmenityRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->amenityService->create($data);

        return redirect()->route('dashboard.amenities.index')->with('success', __('messages.amenity.amenity_added'));
    }

    /**
     * Show the form for editing the specified amenity.
     */
    public function edit(Amenity $amenity): View
    {
        return view('dashboard.amenities.edit', compact('amenity'));
    }

    /**
     * Update the specified amenity.
     */
    public function update(UpdateAmenityRequest $request, Amenity $amenity): RedirectResponse
    {
        $data = $request->validated();

        $this->amenityService->update($amenity, $data);

        return redirect()->route('dashboard.amenities.index')->with('success', __('messages.amenity.amenity_updated'));
    }

    /**
     * Remove the specified amenity.
     */
    public function destroy(Amenity $amenity): RedirectResponse
    {
        $this->amenityService->delete($amenity);

        return redirect()->route('dashboard.amenities.index')->with('success', __('messages.amenity.amenity_updated'));
    }
}
