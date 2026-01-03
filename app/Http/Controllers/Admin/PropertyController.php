<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\AmenityService;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Property service instance
     */
    protected PropertyService $propertyService;
    protected AmenityService $amenityService;

    /**
     * PropertyController constructor.
     *
     * @param PropertyService $propertyService
     * @param AmenityService $amenityService
     */
    public function __construct(PropertyService $propertyService, AmenityService $amenityService)
    {
        $this->propertyService = $propertyService;
        $this->amenityService = $amenityService;
    }

    /**
     * Display a listing of properties with optional filters.
     *
     * Filter input is read from the request query via the request() helper.
     *
     * @return View
     */
    public function index(): View
    {
        // Read filters from query string (no FormRequest used here)
        $filters = request()->only(['amenity_ids']);

        $properties = $this->propertyService->getAllWithFilters($filters);
        $amenities = $this->amenityService->getAll();

        return view('dashboard.properties.index', compact('properties', 'amenities', 'filters'));
    }

    /**
     * Show the form for creating a new property.
     *
     * @return View
     */
    public function create(): View
    {
        $amenities = $this->amenityService->getAll();
        // Add other supporting data (property types, users, etc.) as needed
        return view('dashboard.properties.create', compact('amenities'));
    }

    /**
     * Store a newly created property using StorePropertyRequest.
     *
     * Validation is handled by the FormRequest.
     *
     * @param StorePropertyRequest $request
     * @return RedirectResponse
     */
    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->propertyService->create($data);

        return redirect()->route('dashboard.properties.index')->with('success', 'Property created.');
    }

    /**
     * Show the form for editing the specified property.
     *
     * @param Property $property
     * @return View
     */
    public function edit(Property $property): View
    {
        $property->load('amenities');
        $amenities = $this->amenityService->getAll();

        return view('dashboard.properties.edit', compact('property', 'amenities'));
    }

    /**
     * Update the specified property using UpdatePropertyRequest.
     *
     * Validation is handled by the FormRequest.
     *
     * @param UpdatePropertyRequest $request
     * @param Property $property
     * @return RedirectResponse
     */
    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $data = $request->validated();

        $this->propertyService->update($property, $data);

        return redirect()->route('dashboard.properties.index')->with('success', 'Property updated.');
    }

    /**
     * Remove the specified property.
     *
     * @param Property $property
     * @return RedirectResponse
     */
    public function destroy(Property $property): RedirectResponse
    {
        $this->propertyService->delete($property);

        return redirect()->route('dashboard.properties.index')->with('success', 'Property deleted.');
    }
}
