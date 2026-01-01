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
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;

        // Protect all pages so only admin users can access
        $this->middleware(['auth', 'checkRole:admin']);

    }

    /**
     * GET /dashboard/admin/properties
     * Displays the list of properties (index.blade.php)
     */
    public function index(Request $request)
    {
        // Use the same filters as in the service (page, limit, other filters)
        $properties = $this->propertyService->getAll($request->all());

        return view('dashboard.admin.properties.index', compact('properties'));
    }

    /**
     * GET /dashboard/admin/properties/create
     * Shows the form to create a new property (create.blade.php)
     */
    public function create()
    {
        // If you need to load data for form options (types, amenities), do it here
        // $types = PropertyType::all();
        // $amenities = Amenity::all();

        return view('dashboard.admin.properties.create' /*, compact('types','amenities') */);
    }

    /**
     * POST /dashboard/admin/properties
     * Stores a new property in the database
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
     * GET /dashboard/admin/properties/{property}
     * Shows a single property details (show.blade.php)
     */
    public function show(Property $property)
    {
        $property->load(['propertyType', 'mainImage', 'amenities']);

        return view('dashboard.admin.properties.show', compact('property'));
    }

    /**
     * GET /dashboard/admin/properties/{property}/edit
     * Shows the form to edit a property (edit.blade.php)
     */
    public function edit(Property $property)
    {
        $property->load(['propertyType', 'amenities']);
        // $types = PropertyType::all();
        // $amenities = Amenity::all();

        return view('dashboard.admin.properties.edit' /*, compact('property','types','amenities') */, compact('property'));
    }

    /**
     * PUT /dashboard/admin/properties/{property}
     * Updates a property in the database
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
     * DELETE /dashboard/admin/properties/{property}
     * Deletes a property from the database
     */
    public function destroy(Property $property)
    {
        $this->propertyService->delete($property);

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property has been successfully deleted.');
    }

    /**
     * GET /dashboard/admin/properties/types
     * Shows the property types management page (types.blade.php)
     */
    public function types()
    {
        return view('dashboard.admin.properties.types');
    }
}
