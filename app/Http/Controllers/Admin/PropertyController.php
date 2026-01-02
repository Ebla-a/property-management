<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Property service instance
     */
    protected PropertyService $propertyService;

    /**
     * Constructor
     *
     * Apply authentication and admin role middleware
     */
    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;

        // Ensure only authenticated admin users can access these pages
        $this->middleware(['auth', 'checkRole:admin']);
    }

    /**
     * Display a listing of properties
     *
     * GET /dashboard/admin/properties
     */
    public function index(Request $request)
    {
        // Retrieve properties with filters (pagination, search, etc.)
        $properties = $this->propertyService->getAll($request->all());

        // Note: No "admin" folder in views
        return view('dashboard.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property
     *
     * GET /dashboard/admin/properties/create
     */
    public function create()
    {
        return view('dashboard.properties.create');
    }

    /**
     * Store a newly created property in storage
     *
     * POST /dashboard/admin/properties
     */
    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();

        $this->propertyService->create($data);

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property has been successfully added.');
    }

    /**
     * Display the specified property
     *
     * GET /dashboard/admin/properties/{property}
     */
    public function show(Property $property)
    {
        $property->load(['propertyType', 'mainImage', 'amenities']);

        return view('dashboard.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property
     *
     * GET /dashboard/admin/properties/{property}/edit
     */
    public function edit(Property $property)
    {
        $property->load(['propertyType', 'amenities']);

        return view('dashboard.properties.edit', compact('property'));
    }

    /**
     * Update the specified property in storage
     *
     * PUT /dashboard/admin/properties/{property}
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $data = $request->validated();

        $this->propertyService->update($property, $data);

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property has been successfully updated.');
    }

    /**
     * Remove the specified property from storage
     *
     * DELETE /dashboard/admin/properties/{property}
     */
    public function destroy(Property $property)
    {
        $this->propertyService->delete($property);

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property has been successfully deleted.');
    }

    /**
     * Display property types management page
     *
     * GET /dashboard/admin/properties/types
     */
    public function types()
    {
        return view('dashboard.properties.types');
    }
}
