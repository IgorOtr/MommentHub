<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'event_id',
        'name',
        'slug',
        'description',
        'google_drive_url',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }
}
