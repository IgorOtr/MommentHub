<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'name',
        'mime_type',
        'file_type',
        'google_drive_file_id',
        'google_drive_url',
        'thumbnail_url',
        'preview_url',
        'download_url',
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }
}
