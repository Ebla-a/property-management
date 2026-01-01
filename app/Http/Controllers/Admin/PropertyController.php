<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Display a listing of the resource.
     * GET /admin/properties
     */
    public function index(Request $request)
    {
        // Pass query params to service for filtering/sorting/pagination
        $properties = $this->propertyService->getAll($request->all());

        // PropertyResource::collection works with paginators too
        return PropertyResource::collection($properties);
    }

    /**
     * Store a newly created resource in storage.
     * POST /admin/properties
     */
    public function store(StorePropertyRequest $request)
    {
        $property = $this->propertyService->create(
            $request->validated()
        );

        return new PropertyResource(
            $property->load(['propertyType', 'mainImage', 'amenities'])
        );
    }

    /**
     * Display the specified resource.
     * GET /admin/properties/{property}
     */
    public function show(Property $property)
    {
        return new PropertyResource(
            $property->load(['propertyType', 'mainImage', 'amenities'])
        );
    }

    /**
     * Update the specified resource in storage.
     * PUT /admin/properties/{property}
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $property = $this->propertyService->update(
            $property,
            $request->validated()
        );

        return new PropertyResource(
            $property->load(['propertyType', 'mainImage', 'amenities'])
        );
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /admin/properties/{property}
     */
    public function destroy(Property $property)
    {
        $this->propertyService->delete($property);

        return response()->json([
            'message' => 'Property deleted successfully'
        ]);
    }
}
