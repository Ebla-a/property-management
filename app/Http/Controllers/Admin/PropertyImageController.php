<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyImagesRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;

class PropertyImageController extends Controller
{
    public function store(StorePropertyImagesRequest $request, int $property, ImageService $imageService)
    {
        if (!DB::table('properties')->where('id', $property)->exists()) {
            return redirect()->back()->with('error', 'Property not found');
        }

        $images = $imageService->uploadPropertyImages(
            $property,
            $request->file('images'),
            $request->input('alt')
        );

        return redirect()->back()->with('success', 'Images uploaded successfully');
    }
}
