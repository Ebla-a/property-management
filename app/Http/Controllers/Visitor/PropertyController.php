<?php

namespace App\Http\Controllers\Visitor;

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
     * Display the specified resource.
     * GET /admin/properties/{property}
     */
public function show($id)
{
    $property = Property::with(['propertyType', 'mainImage', 'amenities'])->find($id);

    if (!$property) {
        return response()->json([
            'message' => 'Property not found'
        ], 404);
    }

    return new PropertyResource($property);
}


}
