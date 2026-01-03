<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Amenity;
use App\Services\AmenityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AmenityController extends Controller
{
    protected AmenityService $amenityService;

    /**
     * Constructor.
     *
     * @param AmenityService $amenityService
     */
    public function __construct(AmenityService $amenityService)
    {
        $this->amenityService = $amenityService;
    }

    /**
     * Display a listing of amenities.
     *
     * @return View
     */
    public function index(): View
    {
        $amenities = $this->amenityService->getAll();
        return view('admin.amenities.index', compact('amenities'));
    }

    /**
     * Show the form for creating a new amenity.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.amenities.create');
    }

    /**
     * Store a newly created amenity.
     *
     * @param StoreAmenityRequest $request
     * @return RedirectResponse
     */
    public function store(StoreAmenityRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->amenityService->create($data);

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity added.');
    }

    /**
     * Show the form for editing the specified amenity.
     *
     * @param Amenity $amenity
     * @return View
     */
    public function edit(Amenity $amenity): View
    {
        return view('admin.amenities.edit', compact('amenity'));
    }

    /**
     * Update the specified amenity.
     *
     * @param UpdateAmenityRequest $request
     * @param Amenity $amenity
     * @return RedirectResponse
     */
    public function update(UpdateAmenityRequest $request, Amenity $amenity): RedirectResponse
    {
        $data = $request->validated();

        $this->amenityService->update($amenity, $data);

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated.');
    }

    /**
     * Remove the specified amenity.
     *
     * @param Amenity $amenity
     * @return RedirectResponse
     */
    public function destroy(Amenity $amenity): RedirectResponse
    {
        $this->amenityService->delete($amenity);

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity deleted.');
    }
}
