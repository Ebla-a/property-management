<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyImagesRequest;
use App\Http\Resources\PropertyImageResource;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;

class PropertyImageController extends Controller
{
    public function store(StorePropertyImagesRequest $request, int $property, ImageService $imageService)
    {
        
        if (!DB::table('properties')->where('id', $property)->exists()) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        $images = $imageService->uploadPropertyImages(
            $property,
            $request->file('images'),
            $request->input('alt')
        );

        return response()->json([
            'message' => 'Images uploaded successfully',
            'data' => PropertyImageResource::collection($images),
        ], 201);
    }
}
