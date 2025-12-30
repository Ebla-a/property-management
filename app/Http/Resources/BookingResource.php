<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    //  Resources are The format of the data returned by the API
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'status' =>$this->status,
            'scheduled_at' => $this->scheduled_at?->format('Y-m-d H:i'),
            'notes' =>$this->notes,

            'property'=>[
                'id' =>$this->property->id,
                'title'=>$this->property->title,
                'city' =>$this->property->city,
            ],
            'customer' =>[
                'id' =>$this->customer->id,
                'name' =>$this->customer->name,
                'email' =>$this->customer->email,
            ],
            'employee' => $this->employee
          ?[
        'id'   => $this->employee->id,
        'name' => $this->employee->name,
         ]
    : null,
        ];
    }
    /**
     * use meta data in dashbord 
     * @param mixed $request
     * @return array{meta: array{created_at: mixed, updated_at: mixed}}
     */
    public function with($request)
    {
        return [
            'meta' =>[
                'created_at' =>$this->created_at?->diffForHumans(),
                'updated_at' =>$this->updated_at?->diffForHumans(),
            ]
            ];
    }
}
