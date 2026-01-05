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
        // تحقق إن العقار موجود
        if (!DB::table('properties')->where('id', $property)->exists()) {
            return redirect()->back()->with('error', 'Property not found');
        }

        // رفع الصور
        $images = $imageService->uploadPropertyImages(
            $property,
            $request->file('images'),
            $request->input('alt')
        );

        // إعادة توجيه للصفحة السابقة مع رسالة نجاح
        return redirect()->back()->with('success', 'Images uploaded successfully');
    }
}
