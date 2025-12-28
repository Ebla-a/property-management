<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Services\RatingService;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function store(StoreRatingRequest $request)
    {
        $data = $request->validated();
        $rating = $this->ratingService->addRating(
            Auth::id(),           
            $data['booking_id'],    
            $data['score'],        
            $data['comment'] ?? null
        );

        return response()->json([
            'message' => 'Rating added successfully',
            'rating' => $rating
        ]);
    }
}
