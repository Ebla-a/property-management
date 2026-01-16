<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'rating'  => $this->rating,
            'comment' => $this->comment,

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id'   => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),

            'property' => $this->whenLoaded('property', function () {
                return [
                    'id'    => $this->property->id,
                    'title' => $this->property->title,
                ];
            }),

            'booking_id' => $this->booking_id,

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
