<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Models\User;
use App\Services\AmenityService;
use App\Services\PropertyService;
use App\Notifications\PropertyActionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Support\Arr;

class PropertyController extends Controller
{
    protected PropertyService $propertyService;

    protected AmenityService $amenityService;

    public function __construct(PropertyService $propertyService, AmenityService $amenityService)
    {
        $this->propertyService = $propertyService;
        $this->amenityService = $amenityService;
    }

    /**
     * Display a listing of properties with optional filters (paginated).
     */
    public function index(): View
    {
        $filters = collect(request()->only([
            'amenity_ids',
            'property_types',
            'type',
            'city',
            'min_price',
            'max_price',
            'sort',
            'order',
            'limit',
        ]));

        // If legacy single `type` param exists but no `property_types`, map it to property_types[]
        $filters = $filters
            ->when(
                // condition: type present AND property_types not present
                $filters->get('type') && ! $filters->has('property_types'),
                function ($col) {
                    // make sure it's an array (so service expects array)
                    return $col->put('property_types', Arr::wrap($col->get('type')));
                }
            )
            // Ensure amenity_ids is array (when select multiple disabled or single selected)
            ->when(
                $filters->has('amenity_ids') && ! is_array($filters->get('amenity_ids')),
                function ($col) {
                    return $col->put('amenity_ids', Arr::wrap($col->get('amenity_ids')));
                }
            )
            ->all();

        $properties = $this->propertyService->getPaginated($filters);

        $amenities = $this->amenityService->getAll();
        $propertyTypes = \App\Models\PropertyType::all();

        return view('dashboard.properties.index', compact('properties', 'amenities', 'filters', 'propertyTypes'));
    }

    public function create(): View
    {
        $amenities = $this->amenityService->getAll();
        $propertyTypes = \App\Models\PropertyType::all();

        return view('dashboard.properties.create', compact('amenities', 'propertyTypes'));
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $property = $this->propertyService->create($data);

        if ($request->hasFile('images')) {
            app(\App\Services\ImageService::class)->upload(
                $property,
                $request->file('images'),
                null
            );
        }

        // Notify admins and employees about the new property
        $title = $property->title ?? "Property #{$property->id}";
        $by = auth()->user() ? auth()->user()->name : 'System';
        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new PropertyActionNotification('created', $title, $by));
        }

        return redirect()->route('dashboard.properties.index')->with('success',  __('messages.property.add_property'));
    }

    public function edit(Property $property): View
    {
        $property->load('amenities');
        $amenities = $this->amenityService->getAll();
        $propertyTypes = \App\Models\PropertyType::all();

        return view('dashboard.properties.edit', compact('property', 'amenities', 'propertyTypes'));
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $data = $request->validated();

        $this->propertyService->update($property, $data);

        // Refresh to get latest title (in case it was updated)
        $property->refresh();

        // Notify admins and employees about the update
        $title = $property->title ?? "Property #{$property->id}";
        $by = auth()->user() ? auth()->user()->name : 'System';
        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new PropertyActionNotification('updated', $title, $by));
        }

        return redirect()->route('dashboard.properties.index')->with('success',  __('messages.property.updated'));
    }

    public function destroy(Property $property): RedirectResponse
    {
        // capture title before deletion
        $title = $property->title ?? "Property #{$property->id}";
        $id = $property->id;
        $by = auth()->user() ? auth()->user()->name : 'System';

        $this->propertyService->delete($property);

        // Notify admins and employees about the deletion
        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new PropertyActionNotification('deleted', $title, $by));
        }

        return redirect()->route('dashboard.properties.index')->with('success',  __('messages.property.deleted'));
    }

    public function show(Property $property): View
    {
        $property->load(['images', 'amenities']);

        return view('dashboard.properties.show', compact('property'));
    }
}
