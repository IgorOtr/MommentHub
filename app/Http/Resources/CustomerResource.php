<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'tertiary_color' => $this->tertiary_color,
            'logo_url' => $this->logoUrl(),
            'stores' => StoreResource::collection($this->whenLoaded('stores')),
        ];
    }
}
