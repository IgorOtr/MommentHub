<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'store_id' => $this->store_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'event_date' => $this->event_date?->toDateString(),
            'logo_url' => $this->logoUrl(),
            'cover_image' => 'http://localhost:8070/storage/'.$this->cover_image,
            'folders' => FolderResource::collection($this->whenLoaded('folders')),
        ];
    }
}
