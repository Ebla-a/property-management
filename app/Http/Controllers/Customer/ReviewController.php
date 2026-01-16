<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();

        $review = $this->reviewService->addRating(Auth::id(),$data);

        $review->load(['user', 'property']);
        
        return response()->json([
            'message' => 'Rating added successfully',
            'rating' => new ReviewResource($review),
        ]);
    }
}