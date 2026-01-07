<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyImagesRequest;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PropertyImageController extends Controller
{
    // Property Images Management Page (Blade)
    public function index(Property $property)
    {
        $images = PropertyImage::where('property_id', $property->id)
            ->latest()
            ->get(); // soft deleted excluded by default

        return view('dashboard.properties.images', compact('property', 'images'));
    }

    // Upload Property Images (Admin Blade)
    public function store(StorePropertyImagesRequest $request, Property $property, ImageService $imageService)
    {
        $imageService->upload(
            $property,
            $request->file('images'),
            $request->input('alt')
        );

        return redirect()
            ->route('admin.properties.images.index', $property->id)
            ->with('success', 'Images uploaded successfully');
    }

    // Set as Main Image
    public function setMain(Request $request, Property $property, PropertyImage $image, ImageService $imageService)
    {
        $imageService->setMain($property, $image);
        return back()->with('success', 'Main image has been set successfully');
    }

    // Soft Delete
    public function destroy(Property $property, PropertyImage $image, ImageService $imageService)
    {
        $imageService->softDelete($property, $image);
        return back()->with('success', 'Image has been soft deleted successfully');
    }

    // Permanent Delete
    public function forceDestroy(Property $property, PropertyImage $image, ImageService $imageService)
    {
        $imageService->forceDelete($property, $image);
        return back()->with('success', 'Image has been permanently deleted');
    }

    // Trashed Images Page (Blade)
    public function trashed(Property $property)
    {
        $trashedImages = PropertyImage::onlyTrashed()
            ->where('property_id', $property->id)
            ->latest()
            ->get();

        return view('dashboard.properties.images_trashed', compact('property', 'trashedImages'));
    }

    // Restore soft deleted image
    public function restore(Property $property, $image, ImageService $imageService)
    {
        $img = PropertyImage::withTrashed()
            ->where('property_id', $property->id)
            ->findOrFail($image);

        $imageService->restore($property, $img);

        return back()->with('success', 'Image restored successfully.');
    }
}
