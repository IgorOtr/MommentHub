<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'google_drive_url' => $this->google_drive_url,
            'is_public' => $this->is_public,
            'media_files' => MediaFileResource::collection($this->whenLoaded('mediaFiles')),
        ];
    }
}
