<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'folder_id' => $this->folder_id,
            'name' => $this->name,
            'mime_type' => $this->mime_type,
            'file_type' => $this->file_type,
            'google_drive_url' => $this->google_drive_url,
            'thumbnail_url' => $this->thumbnail_url,
            'preview_url' => $this->preview_url,
            'download_url' => $this->download_url,
        ];
    }
}
