<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'type'          => $this->propertyType?->name,
            'city'          => $this->city,
            'neighborhood'  => $this->neighborhood,
            'address'       => $this->address,
            'rooms'         => $this->rooms,
            'area'          => $this->area,
            'price'         => $this->price,
            'status'        => $this->status,
            'is_furnished'  => $this->is_furnished,
            'description'  => $this->description,

            'main_image' => $this->mainImage?->path,

            'amenities' => $this->amenities->pluck('name'),

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
